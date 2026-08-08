<?php

namespace App\Http\Controllers\Admin;

use App\Attendee;
use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActivateVoucherRequest;
use App\Price;
use App\Voucher;
use App\VoucherAttendee;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Generator;
use Symfony\Component\HttpFoundation\Response;

class AttendeesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('attendee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Attendee::with('event')->orderByDesc('created_at');

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->input('event_id'));
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('tax_code', 'like', "%{$search}%");
            });
        }

        $attendees = $query->get();
        $events = Event::orderBy('name')->get();

        $activeVouchers = Voucher::where('status', 'active')
            ->when($request->filled('event_id'), function ($q) use ($request) {
                return $q->where(function ($qq) use ($request) {
                    $qq->where('event_id', $request->input('event_id'))->orWhereNull('event_id');
                });
            })
            ->orderBy('code')
            ->get()
            ->filter(fn ($v) => $v->isAvailable());

        return view('admin.attendees.index', compact('attendees', 'events', 'activeVouchers'));
    }

    public function show(Attendee $attendee)
    {
        abort_if(Gate::denies('attendee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attendee->load('event', 'voucher');

        $activeVouchers = Voucher::where('status', 'active')
            ->when($attendee->event_id, function ($q) use ($attendee) {
                return $q->where(function ($qq) use ($attendee) {
                    $qq->where('event_id', $attendee->event_id)->orWhereNull('event_id');
                });
            })
            ->orderBy('code')
            ->get()
            ->filter(fn ($v) => $v->isAvailable());

        return view('admin.attendees.show', compact('attendee', 'activeVouchers'));
    }

    public function showQr(Attendee $attendee)
    {
        abort_if(Gate::denies('attendee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (empty($attendee->qr)) {
            $attendee->regenerateQr();
        }

        $qr = (new Generator)->format('svg')
            ->size(400)
            ->errorCorrection('H')
            ->generate($attendee->qr);

        return response($qr, 200, ['Content-Type' => 'image/svg+xml']);
    }

    public function generateQr(Attendee $attendee)
    {
        abort_if(Gate::denies('attendee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attendee->regenerateQr();

        return back()->with('message', 'Mã QR mới đã được tạo: ' . $attendee->qr);
    }

    public function sendTicket(Attendee $attendee)
    {
        abort_if(Gate::denies('attendee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (empty($attendee->qr)) {
            $attendee->regenerateQr();
        }

        // Generate confirmation token trước khi gửi email
        if (empty($attendee->confirmation_token)) {
            $attendee->generateConfirmationToken();
        }

        try {
            \Illuminate\Support\Facades\Mail::to($attendee->email)
                ->send(new \App\Mail\EventTicket($attendee));

            return back()->with('message', 'Đã gửi vé đến ' . $attendee->email);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send event ticket email', [
                'attendee' => $attendee->id,
                'error'    => $e->getMessage(),
            ]);

            return back()->with('error', 'Gửi vé thất bại: ' . $e->getMessage());
        }
    }

    public function sendVerify(Attendee $attendee)
    {
        abort_if(Gate::denies('attendee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            \Illuminate\Support\Facades\Mail::to($attendee->email)
                ->send(new \App\Mail\EventRegistrationConfirmation($attendee));

            return back()->with('message', 'Đã gửi mail verify đến ' . $attendee->email);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send event verify email', [
                'attendee' => $attendee->id,
                'error'    => $e->getMessage(),
            ]);

            return back()->with('error', 'Gửi mail verify thất bại: ' . $e->getMessage());
        }
    }

    public function sendVoucherEmail(Attendee $attendee)
    {
        abort_if(Gate::denies('attendee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$attendee->voucher) {
            return back()->with('error', 'Khách này chưa có voucher để gửi email.');
        }

        try {
            \Illuminate\Support\Facades\Mail::to($attendee->email)
                ->send(new \App\Mail\VoucherAssignedMail($attendee->voucher, $attendee, null));

            $VoucherAttendee = \App\VoucherAttendee::where('voucher_id', $attendee->voucher_id)
                ->where('attendee_id', $attendee->id)
                ->where('status', '!=', 'revoked')
                ->first();
            if ($VoucherAttendee) {
                $VoucherAttendee->update(['assigned_at' => now()]);
            }

            return back()->with('message', 'Đã gửi email voucher ' . $attendee->voucher->code . ' đến ' . $attendee->email);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send voucher email', [
                'attendee' => $attendee->id,
                'voucher'  => $attendee->voucher_id,
                'error'    => $e->getMessage(),
            ]);

            return back()->with('error', 'Gửi email voucher thất bại: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        abort_if(Gate::denies('attendee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'id'     => ['required', 'exists:attendees,id'],
            'status' => ['required', 'in:pending,confirmed,attended,cancelled'],
        ]);

        $attendee = Attendee::findOrFail($request->input('id'));
        $attendee->update(['status' => $request->input('status')]);

        return response()->json([
            'success' => true,
            'label'   => $attendee->status_label,
        ]);
    }

    public function destroy(Attendee $attendee)
    {
        abort_if(Gate::denies('attendee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attendee->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('attendee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Attendee::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function activateVoucher(ActivateVoucherRequest $request, Attendee $attendee)
    {
        $voucher = Voucher::findOrFail($request->input('voucher_id'));
        $sendEmail = (bool) $request->boolean('send_email', true);
        $force = (bool) $request->boolean('force', false);
        $note = $request->input('note');

        if (!$voucher->isAvailable()) {
            $voucher->syncStatusFromDates();

            return response()->json([
                'status'  => 'error',
                'code'    => $voucher->status === 'expired' ? 'VOUCHER_EXPIRED' : 'VOUCHER_UNAVAILABLE',
                'message' => $voucher->status === 'expired'
                    ? 'Voucher này đã hết hạn sử dụng.'
                    : ($voucher->max_uses !== null && $voucher->used_count >= $voucher->max_uses
                        ? 'Voucher này đã hết lượt sử dụng.'
                        : 'Voucher không hợp lệ hoặc chưa có hiệu lực.'),
            ], 422);
        }

        if ($attendee->voucher_id && !$force) {
            $current = Voucher::find($attendee->voucher_id);

            return response()->json([
                'status'           => 'warning',
                'code'             => 'ALREADY_HAS_VOUCHER',
                'message'          => 'Khách này đã có voucher \'' . ($current->code ?? '') . '\' đang áp dụng. Bạn có muốn thay thế?',
                'current_voucher'  => [
                    'code' => $current->code ?? '',
                    'name' => $current->name ?? '',
                ],
            ], 409);
        }

        $oldVoucherId = $attendee->voucher_id;

        // If replacing an existing voucher, revoke the old one first.
        if ($oldVoucherId && $oldVoucherId !== $voucher->id) {
            $this->revokeAttendeeVoucher($attendee);
        }

        $basePrice = $this->attendeeBasePrice($attendee);

        $attendee->update([
            'voucher_id'      => $voucher->id,
            'voucher_code'    => $voucher->code,
            'discount_amount' => $voucher->calculateDiscount($basePrice),
        ]);

        VoucherAttendee::updateOrCreate(
            ['voucher_id' => $voucher->id, 'attendee_id' => $attendee->id],
            [
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'used_at'     => now(),
                'note'        => $note,
                'status'      => 'used',
            ]
        );

        $voucher->increment('used_count');

        $emailSent = false;
        if ($sendEmail) {
            try {
                Mail::to($attendee->email)->send(new \App\Mail\VoucherAssignedMail($voucher, $attendee, $note));
                $emailSent = true;
            } catch (\Throwable $e) {
                Log::error('Failed to send activated voucher email', [
                    'attendee' => $attendee->id,
                    'voucher'  => $voucher->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'status'           => 'success',
            'message'          => 'Đã kích hoạt voucher ' . $voucher->code . ' cho ' . $attendee->name . '.',
            'data'             => [
                'attendee_name'    => $attendee->name,
                'voucher_code'     => $voucher->code,
                'voucher_name'     => $voucher->name,
                'discount_applied' => $voucher->discount_label . ($attendee->discount_amount > 0 ? ' — ' . number_format($attendee->discount_amount, 0, ',', '.') . 'đ' : ''),
                'email_sent'       => $emailSent,
            ],
        ]);
    }

    public function revokeVoucher(Request $request, Attendee $attendee)
    {
        abort_if(Gate::denies('voucher_assign'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        if (!$attendee->voucher_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Khách này chưa có voucher nào để thu hồi.',
            ], 422);
        }

        $voucher = Voucher::find($attendee->voucher_id);
        $this->revokeAttendeeVoucher($attendee);

        return response()->json([
            'status'  => 'success',
            'message' => 'Đã thu hồi voucher ' . ($voucher->code ?? '') . ' của ' . $attendee->name . '.',
        ]);
    }

    public function voucherDetail(Attendee $attendee)
    {
        abort_if(Gate::denies('attendee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attendee->load('voucher', 'voucherAssignment');

        $assignment = $attendee->voucherAssignment;

        if (!$attendee->voucher) {
            return response()->json([
                'attendee'   => ['id' => $attendee->id, 'name' => $attendee->name, 'email' => $attendee->email],
                'voucher'    => null,
                'assignment' => null,
                'message'    => 'Khách này chưa được gán hoặc áp dụng voucher nào.',
            ]);
        }

        return response()->json([
            'attendee'   => ['id' => $attendee->id, 'name' => $attendee->name, 'email' => $attendee->email],
            'voucher'    => [
                'id'              => $attendee->voucher->id,
                'code'            => $attendee->voucher->code,
                'name'            => $attendee->voucher->name,
                'type'            => $attendee->voucher->type,
                'value'           => $attendee->voucher->value,
                'description'     => $attendee->voucher->description,
                'discount_label'  => $attendee->voucher->discount_label,
                'discount_amount' => $attendee->discount_amount,
                'valid_until'     => $attendee->voucher->valid_until ? $attendee->voucher->valid_until->toDateTimeString() : null,
                'status'          => $attendee->voucher->status,
            ],
            'assignment' => $assignment ? [
                'source'      => 'admin_activated',
                'assigned_by' => $assignment->assigner?->name,
                'assigned_at' => $assignment->assigned_at ? $assignment->assigned_at->toDateTimeString() : null,
                'used_at'     => $assignment->used_at ? $assignment->used_at->toDateTimeString() : null,
                'note'        => $assignment->note,
                'status'      => $assignment->status,
            ] : null,
        ]);
    }

    protected function revokeAttendeeVoucher(Attendee $attendee): void
    {
        $voucherId = $attendee->voucher_id;

        $attendee->update([
            'voucher_id'      => null,
            'voucher_code'    => null,
            'discount_amount' => 0,
        ]);

        if ($voucherId) {
            Voucher::where('id', $voucherId)->decrement('used_count');
            VoucherAttendee::where('voucher_id', $voucherId)
                ->where('attendee_id', $attendee->id)
                ->where('status', '!=', 'revoked')
                ->update(['status' => 'revoked']);
        }
    }

    protected function attendeeBasePrice(Attendee $attendee): ?float
    {
        if (!$attendee->event_id) {
            return null;
        }

        $min = Price::where('event_id', $attendee->event_id)->min('price');

        return $min !== null ? (float) $min : null;
    }

    public function export(Request $request)
    {
        abort_if(Gate::denies('attendee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Attendee::with('event');

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->input('event_id'));
        }

        $attendees = $query->orderByDesc('created_at')->get();

        $headers = ['ID', 'Sự kiện', 'Họ và tên', 'Email công ty', 'Tên công ty', 'MST', 'Số điện thoại', 'Quy mô doanh nghiệp', 'Sản phẩm quan tâm', 'Trạng thái', 'Thời gian đăng ký'];
        $rows = $attendees->map(function ($attendee) {
            return [
                $attendee->id,
                $attendee->event->name ?? '',
                $attendee->name,
                $attendee->email,
                $attendee->company,
                $attendee->tax_code,
                $attendee->phone,
                $attendee->company_size_label,
                $attendee->interested_products,
                $attendee->status_label,
                $attendee->created_at ? $attendee->created_at->format('d/m/Y H:i') : '',
            ];
        });

        $filename = 'attendees_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');

            fputs($out, $bom = chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}