<?php

namespace App\Http\Controllers\Admin;

use App\Attendee;
use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Http\Requests\StoreVoucherRequest;
use App\Voucher;
use App\VoucherAttendee;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('voucher_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Voucher::with('event')->latest();

        if ($request->filled('event_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('event_id', $request->input('event_id'))->orWhereNull('event_id');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->get();
        $events   = Event::orderBy('name')->get();

        $activeVouchers = Voucher::where('status', 'active')
            ->orderBy('code')
            ->get()
            ->filter(function ($voucher) {
                return $voucher->max_uses === null || $voucher->used_count < $voucher->max_uses;
            })
            ->values();
        $attendees = Attendee::where('event_id', current_event_id())
            ->whereNull('voucher_id')
            ->whereNotIn('id', VoucherAttendee::where('status', '!=', 'revoked')->pluck('attendee_id'))
            ->orderByDesc('created_at')
            ->get();

        return view('admin.vouchers.index', compact('vouchers', 'events', 'activeVouchers', 'attendees'));
    }

    public function create()
    {
        abort_if(Gate::denies('voucher_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = Event::orderBy('name')->get();

        return view('admin.vouchers.create', compact('events'));
    }

    public function store(StoreVoucherRequest $request)
    {
        $data = $this->voucherData($request);
        $data['created_by'] = auth()->id();

        $voucher = Voucher::create($data);

        return redirect()->route('admin.vouchers.edit', $voucher)
            ->with('message', 'Tạo voucher ' . $voucher->code . ' thành công.');
    }

    public function show(Voucher $voucher)
    {
        abort_if(Gate::denies('voucher_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $voucher->load('event', 'creator');
        $voucher->syncStatusFromDates();

        $attendees = $voucher->attendees()->with('event')->get();

        return view('admin.vouchers.show', compact('voucher', 'attendees'));
    }

    public function edit(Voucher $voucher)
    {
        abort_if(Gate::denies('voucher_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $voucher->syncStatusFromDates();

        $events = Event::orderBy('name')->get();
        $attendees = Attendee::where('event_id', $voucher->event_id ?: current_event_id())
            ->whereNull('voucher_id')
            ->whereNotIn('id', VoucherAttendee::where('status', '!=', 'revoked')->pluck('attendee_id'))
            ->orderByDesc('created_at')
            ->get();
        $assignedIds = $voucher->attendees()->pluck('attendees.id');

        return view('admin.vouchers.edit', compact('voucher', 'events', 'attendees', 'assignedIds'));
    }

    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
        $data = $this->voucherData($request);

        if ($voucher->used_count > 0 && $data['code'] !== $voucher->code) {
            return redirect()->back()->withInput()->with('error', 'Không thể sửa mã vì voucher đã được sử dụng.');
        }

        // Unique check excluding self
        $duplicate = Voucher::where('code', $data['code'])->where('id', '!=', $voucher->id)->exists();
        if ($duplicate) {
            return redirect()->back()->withInput()->with('error', 'Mã voucher \'' . $data['code'] . '\' đã tồn tại.');
        }

        $voucher->update($data);

        return redirect()->route('admin.vouchers.edit', $voucher)
            ->with('success', 'Cập nhật voucher thành công.');
    }

    public function destroy(Voucher $voucher)
    {
        abort_if(Gate::denies('voucher_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($voucher->used_count > 0) {
            return redirect()->back()->with('error', 'Không thể xóa voucher đã được sử dụng. Hãy vô hiệu hóa thay vì xóa.');
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Đã xóa voucher.');
    }

    public function deactivate(Voucher $voucher)
    {
        abort_if(Gate::denies('voucher_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $voucher->update(['status' => 'inactive']);

        return redirect()->back()->with('success', 'Voucher ' . $voucher->code . ' đã được vô hiệu hóa.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('voucher_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Voucher::whereIn('id', $request->input('ids', []))
            ->where('used_count', 0)
            ->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function generateCode(Request $request)
    {
        abort_if(Gate::denies('voucher_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return response()->json(['code' => Voucher::generateCode(strtoupper((string) $request->input('prefix', 'VCR')))]);
    }

    public function assign(AssignVoucherRequest $request, Voucher $voucher)
    {
        $attendeeIds = array_unique($request->input('attendee_ids', []));
        $sendEmail   = (bool) $request->boolean('send_email', true);
        $note        = $request->input('note');
        $userId      = auth()->id();

        $assigned = [];
        $skipped  = [];

        foreach ($attendeeIds as $attendeeId) {
            $attendee = Attendee::find($attendeeId);
            if (!$attendee) {
                $skipped[] = ['attendee_id' => $attendeeId, 'name' => '', 'email' => '', 'reason' => 'Không tồn tại'];
                continue;
            }

            $exists = VoucherAttendee::where('voucher_id', $voucher->id)
                ->where('attendee_id', $attendee->id)
                ->where('status', '!=', 'revoked')
                ->exists();

            if ($exists) {
                $skipped[] = ['attendee_id' => $attendee->id, 'name' => $attendee->name, 'email' => $attendee->email, 'reason' => 'Đã được gán voucher này trước đó'];
                continue;
            }

            if ($voucher->max_uses !== null && $voucher->used_count >= $voucher->max_uses) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 'VOUCHER_EXHAUSTED',
                    'message' => 'Voucher chỉ còn ' . ($voucher->remaining_uses ?? 0) . ' lượt dùng, không đủ để gán.',
                ], 422);
            }

            VoucherAttendee::create([
                'voucher_id'  => $voucher->id,
                'attendee_id' => $attendee->id,
                'assigned_by' => $userId,
                'assigned_at' => now(),
                'note'        => $note,
                'status'      => 'assigned',
            ]);

            $voucher->increment('used_count');

            $emailSent = false;
            if ($sendEmail) {
                try {
                    Mail::to($attendee->email)->send(new \App\Mail\VoucherAssignedMail($voucher, $attendee, $note));
                    $emailSent = true;
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send voucher assigned email', [
                        'attendee' => $attendee->id,
                        'voucher'  => $voucher->id,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }

            $assigned[] = ['attendee_id' => $attendee->id, 'name' => $attendee->name, 'email' => $attendee->email, 'email_sent' => $emailSent];
        }

        $message = 'Đã gán voucher cho ' . count($assigned) . ' khách' . (count($skipped) ? ', bỏ qua ' . count($skipped) . ' khách đã có.' : '.');

        return response()->json([
            'status'   => 'success',
            'message'  => $message,
            'assigned' => $assigned ?? [],
            'skipped'  => $skipped,
        ]);
    }

    public function attendees(Voucher $voucher)
    {
        abort_if(Gate::denies('voucher_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attendees = $voucher->attendees()->with('event')->get();

        return response()->json([
            'voucher'   => [
                'code'      => $voucher->code,
                'used_count' => $voucher->used_count,
                'max_uses'  => $voucher->max_uses,
                'remaining' => $voucher->remaining_uses,
            ],
            'attendees' => $attendees,
        ]);
    }

    protected function voucherData(Request $request): array
    {
        $data = $request->only([
            'event_id', 'code', 'name', 'type', 'value', 'description',
            'max_uses', 'is_single_use', 'is_assignable', 'valid_from',
            'valid_until', 'status',
        ]);

        $data['code']             = trim((string)$data['code']);
        $data['value']            = $request->input('value', 0);
        $data['is_single_use']    = $request->boolean('is_single_use', false);
        $data['is_assignable']    = $request->boolean('is_assignable', true);
        $data['description']      = $request->input('description') ?: null;
        $data['valid_from']       = $request->input('valid_from') ?: null;
        $data['valid_until']      = $request->input('valid_until') ?: null;
        $data['event_id']         = $request->filled('event_id') ? $request->input('event_id') : null;

        return $data;
    }
}