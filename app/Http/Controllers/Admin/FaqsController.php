<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFaqRequest;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FaqsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('faq_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faqs = Faq::where('event_id', current_event_id())->get();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        abort_if(Gate::denies('faq_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.faqs.create');
    }

    public function store(StoreFaqRequest $request)
    {
        $data = $request->only(['question', 'answer']);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $faq = Faq::create($data);

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.faqs.index');
    }

    public function edit(Faq $faq)
    {
        abort_if(Gate::denies('faq_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->update($request->only(['question', 'answer']));

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $faq->event_id)
            : redirect()->route('admin.faqs.index');
    }

    public function show(Faq $faq)
    {
        abort_if(Gate::denies('faq_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.faqs.show', compact('faq'));
    }

    public function destroy(Faq $faq)
    {
        abort_if(Gate::denies('faq_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faq->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $faq->event_id)
            : back();
    }

    public function massDestroy(MassDestroyFaqRequest $request)
    {
        Faq::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('faq_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer'   => ['required', 'string'],
        ]);

        $data['event_id'] = $event->id;
        $faq = Faq::create($data);

        return response()->json(['id' => $faq->id, 'question' => $faq->question, 'answer' => $faq->answer]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Faq $faq)
    {
        abort_if(Gate::denies('faq_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($faq->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer'   => ['required', 'string'],
        ]);

        $faq->update($data);

        return response()->json(['id' => $faq->id, 'question' => $faq->question, 'answer' => $faq->answer]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Faq $faq)
    {
        abort_if(Gate::denies('faq_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($faq->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faq->delete();

        return response()->json(['ok' => true]);
    }
}
