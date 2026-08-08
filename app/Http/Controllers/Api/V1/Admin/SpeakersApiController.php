<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use App\Http\Resources\Admin\SpeakerResource;
use App\Speaker;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpeakersApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('speaker_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return SpeakerResource::collection(Speaker::where('event_id', current_event_id())->get());
    }

    public function store(StoreSpeakerRequest $request)
    {
        $speaker = Speaker::create($this->speakerData($request));

        if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
            $speaker->addMedia($photoPath)->toMediaCollection('photo');
        }

        return (new SpeakerResource($speaker))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $speaker->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new SpeakerResource($speaker);
    }

    public function update(UpdateSpeakerRequest $request, Speaker $speaker)
    {
        abort_if((int) $speaker->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $speaker->update($this->speakerData($request));

        if ($request->input('photo', false)) {
            if (!$speaker->photo || $request->input('photo') !== $speaker->photo->file_name) {
                if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
                    $speaker->addMedia($photoPath)->toMediaCollection('photo');
                }
            }
        } elseif ($speaker->photo) {
            $speaker->photo->delete();
        }

        return (new SpeakerResource($speaker))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $speaker->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $speaker->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function speakerData(Request $request)
    {
        $data = $request->only([
            'name',
            'role',
            'company',
            'twitter',
            'facebook',
            'linkedin',
            'description',
            'full_description',
        ]);

        foreach (['twitter', 'facebook', 'linkedin'] as $field) {
            $data[$field] = safe_href($data[$field] ?? null);
        }

        foreach (['description', 'full_description'] as $field) {
            $data[$field] = clean_html($data[$field] ?? null);
        }

        return $data;
    }
}
