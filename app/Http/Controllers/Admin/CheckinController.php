<?php

namespace App\Http\Controllers\Admin;

use App\Attendee;
use App\Event;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckinController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('attendee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = Event::orderBy('name')->get();

        return view('admin.checkin.index', compact('events'));
    }

    public function scan(Request $request)
    {
        abort_if(Gate::denies('attendee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'qr' => ['required', 'string', 'max:50'],
        ]);

        $code = trim($request->input('qr'));

        $attendee = Attendee::with('event')->where('qr', $code)->first();

        if (!$attendee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khách với mã QR này. Vui lòng kiểm tra lại.',
            ], 404);
        }

        if ($attendee->is_checked_in) {
            return response()->json([
                'success' => false,
                'already' => true,
                'message' => 'Vé này đã check-in lúc ' . $attendee->checked_in_at->format('d/m/Y H:i:s'),
                'attendee' => $this->attendeePayload($attendee),
            ], 200);
        }

        $attendee->checkIn(auth()->id());

        return response()->json([
            'success'  => true,
            'message'  => 'Check-in thành công: ' . $attendee->name,
            'attendee' => $this->attendeePayload($attendee),
        ], 200);
    }

    private function attendeePayload(Attendee $attendee): array
    {
        return [
            'id'              => $attendee->id,
            'name'            => $attendee->name,
            'email'           => $attendee->email,
            'company'         => $attendee->company,
            'phone'           => $attendee->phone,
            'ticket_type'     => $attendee->ticket_type,
            'qr'              => $attendee->qr,
            'event'           => $attendee->event->name ?? null,
            'status_label'    => $attendee->status_label,
            'checked_in_at'   => $attendee->checked_in_at ? $attendee->checked_in_at->format('d/m/Y H:i:s') : null,
            'company_size_label' => $attendee->company_size_label,
        ];
    }
}