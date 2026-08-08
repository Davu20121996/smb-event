<?php

namespace App\Http\Controllers\Admin;

use App\ContactMessage;
use App\Event;
use App\LandingLead;
use App\LandingPage;

class HomeController
{
    public function index()
    {
        $totalEventRegistrations = ContactMessage::where('event_id', '>', 0)->count();
        $totalHomeMessages       = ContactMessage::where('event_id', 0)->count();
        $totalLandingLeads       = LandingLead::count();

        $activeEvent = Event::where('is_active', 1)->orderBy('id')->first();

        $landingPages = LandingPage::withCount('leads')->with('leads')->get();

        $events = Event::withCount(['contactMessages', 'speakers'])->orderBy('start_date', 'desc')->get();

        $parseDate = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d) : null;

        $eventStats = [
            'total'      => $events->count(),
            'active'     => $events->where('is_active', true)->count(),
            'upcoming'   => $events->filter(fn ($e) => $parseDate($e->start_date)?->isFuture())->count(),
            'past'       => $events->filter(fn ($e) => $parseDate($e->end_date)?->isPast())->count(),
            'registrations' => $totalEventRegistrations,
        ];

        $landingStats = [
            'total'      => $landingPages->count(),
            'published'  => $landingPages->where('is_published', true)->count(),
            'unpublished' => $landingPages->where('is_published', false)->count(),
            'leads'      => $totalLandingLeads,
        ];

        $recentLeads = LandingLead::with('landingPage')->latest()->limit(6)->get();
        $recentRegistrations = ContactMessage::where('event_id', '>', 0)
            ->with('event')->latest()->limit(6)->get();

        return view('admin.home', compact(
            'totalEventRegistrations',
            'totalHomeMessages',
            'totalLandingLeads',
            'activeEvent',
            'events',
            'eventStats',
            'landingPages',
            'landingStats',
            'recentLeads',
            'recentRegistrations'
        ));
    }
}
