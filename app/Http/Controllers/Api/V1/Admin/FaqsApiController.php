<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Faq;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Http\Resources\Admin\FaqResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FaqsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('faq_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return FaqResource::collection(Faq::where('event_id', current_event_id())->get());
    }

    public function store(StoreFaqRequest $request)
    {
        $data = $request->only(['question', 'answer']);
        $data['event_id'] = current_event_id();
        $faq = Faq::create($data);

        return (new FaqResource($faq))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Faq $faq)
    {
        abort_if(Gate::denies('faq_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $faq->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new FaqResource($faq);
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        abort_if((int) $faq->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $faq->update($request->only(['question', 'answer']));

        return (new FaqResource($faq))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Faq $faq)
    {
        abort_if(Gate::denies('faq_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $faq->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $faq->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
