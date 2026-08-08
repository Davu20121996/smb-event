<?php

namespace App\Http\Controllers\Admin;

use App\ContactMessage;
use App\Event;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactMessagesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('contact_message_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = ContactMessage::with('event')->orderByDesc('created_at');

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->input('event_id'));
        }

        $contactMessages = $query->get();
        $events = Event::orderBy('name')->get();

        return view('admin.contact-messages.index', compact('contactMessages', 'events'));
    }
    public function show(ContactMessage $contact)
    {
        abort_if(Gate::denies('contact_message_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (is_null($contact->read_at)) {
            $contact->update(['read_at' => now()]);
        }

        return view('admin.contact-messages.show', compact('contact'));
    }

    public function destroy(ContactMessage $contact)
    {
        abort_if(Gate::denies('contact_message_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contact->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('contact_message_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ContactMessage::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
