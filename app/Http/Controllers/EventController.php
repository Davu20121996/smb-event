<?php

namespace App\Http\Controllers;

use App\Amenity;
use App\Attendee;
use App\ContactMessage;
use App\Event;
use App\Faq;
use App\Gallery;
use App\Hotel;
use App\KeyBenefit;
use App\Mail\EventRegistrationConfirmation;
use App\Price;
use App\Schedule;
use App\Setting;
use App\Speaker;
use App\Sponsor;
use App\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $event = current_event();

        if (!$event) {
            return view('no-event');
        }

        return $this->show($event);
    }

    public function show($event)
    {
        if (!$event instanceof Event) {
            $event = Event::where('slug', $event)->orWhere('id', $event)->firstOrFail();
        }

        if (!$event->is_active) {
            return view('no-event');
        }

        $settings = $this->loadSettings($event);

        $speakers = Speaker::where('event_id', $event->id)->get();
        $schedules = Schedule::with('speaker')
            ->where('event_id', $event->id)
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_number');
        $venues = Venue::where('event_id', $event->id)->get();
        $hotels = Hotel::where('event_id', $event->id)->get();
        $galleries = Gallery::where('event_id', $event->id)->get();
        $sponsors = Sponsor::where('event_id', $event->id)->get();
        $faqs = Faq::where('event_id', $event->id)->get();
        $prices = Price::with('amenities')->where('event_id', $event->id)->get();
        $amenities = Amenity::with('prices')->where('event_id', $event->id)->get();
        $keyBenefits = KeyBenefit::where('event_id', $event->id)->orderBy('sort_order')->get();

        return view('event', compact(
            'event',
            'settings',
            'speakers',
            'schedules',
            'venues',
            'hotels',
            'galleries',
            'sponsors',
            'faqs',
            'prices',
            'amenities',
            'keyBenefits'
        ));
    }

    public function register(Request $request)
    {
        $event = current_event();

        if (!$event) {
            return redirect()->route('event');
        }

        $validator = Validator::make($request->all(), [
            'name'        => ['required', 'min:4'],
            'email'       => ['required', 'email'],
            'ticket_type' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $attendee = Attendee::create([
            'event_id'    => $event->id,
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'ticket_type' => $request->input('ticket_type'),
            'status'      => 'pending',
        ]);

        $this->sendConfirmationEmail($attendee);

        session(['event_registered' => $event->id]);

        return redirect()->route('event.thank-you');
    }

    public function registerLead(Request $request)
    {
        $event = current_event();

        if (!$event) {
            return response('No active event', 400);
        }

        $validator = Validator::make($request->all(), [
            'name'                => ['required', 'min:2', 'max:255'],
            'email'               => ['required', 'email', 'max:255'],
            'company'             => ['nullable', 'string', 'max:255'],
            'tax_code'            => ['nullable', 'string', 'max:30'],
            'phone'               => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'company_size'        => ['nullable', 'string', 'max:50'],
            'interested_products' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 422);
        }

        $attendee = Attendee::create([
            'event_id'            => $event->id,
            'name'                => $request->input('name'),
            'email'               => $request->input('email'),
            'company'             => $request->input('company'),
            'tax_code'            => $request->input('tax_code'),
            'phone'               => $request->input('phone'),
            'company_size'        => $request->input('company_size'),
            'interested_products' => $request->input('interested_products'),
            'status'              => 'pending',
        ]);

        $this->sendConfirmationEmail($attendee);

        session(['event_registered' => $event->id]);

        return response('OK', 200);
    }

    private function sendConfirmationEmail(Attendee $attendee)
    {
        try {
            Mail::to($attendee->email)->send(new EventRegistrationConfirmation($attendee));
        } catch (\Throwable $e) {
            Log::error('Failed to send event registration confirmation email', [
                'attendee' => $attendee->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    public function thankYou()
    {
        $event = current_event();

        if (!$event) {
            return redirect()->route('event');
        }

        $settings = $this->loadSettings($event);

        return view('event-thank-you', compact('event', 'settings'));
    }

    public function confirmAttendance(string $token)
    {
        $attendee = \App\Attendee::where('confirmation_token', $token)->firstOrFail();

        $event = Event::find($attendee->event_id);

        if (!$event) {
            return redirect()->route('event');
        }

        if (!in_array($attendee->status, ['rsvp_confirmed', 'attended'])) {
            $attendee->update([
                'status'       => 'rsvp_confirmed',
                'confirmed_at' => now(),
            ]);
        }

        $settings = $this->loadSettings($event);

        return view('event-confirmed', compact('event', 'attendee', 'settings'));
    }

    public function verifyAttendance(string $token)
    {
        $attendee = \App\Attendee::where('confirmation_token', $token)->firstOrFail();

        $event = Event::find($attendee->event_id);

        if (!$event) {
            return redirect()->route('event');
        }

        $attendee->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $settings = $this->loadSettings($event);

        return view('event-confirmed', compact('event', 'attendee', 'settings'));
    }

    public function ticketQr(Attendee $attendee)
    {
        if (empty($attendee->qr)) {
            $attendee->regenerateQr();
        }

        $png = Attendee::qrPng($attendee->qr, 320);

        if ($png === null) {
            abort(500, 'Không thể tạo mã QR.');
        }

        return response($png, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'inline; filename="qr-' . $attendee->qr . '.png"',
            'Cache-Control'       => 'public, max-age=604800',
        ]);
    }

    private function loadSettings(Event $event)
    {
        $global = Setting::whereNull('event_id')->pluck('value', 'key')->toArray();
        $eventSettings = Setting::where('event_id', $event->id)->pluck('value', 'key')->toArray();

        return array_merge($global, $eventSettings);
    }
}
