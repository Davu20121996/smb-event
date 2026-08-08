<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySpeakerRequest;
use App\Http\Requests\StoreSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use App\Speaker;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpeakersController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('speaker_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speakers = Speaker::where('event_id', current_event_id())->get();

        return view('admin.speakers.index', compact('speakers'));
    }

    public function create()
    {
        abort_if(Gate::denies('speaker_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.speakers.create');
    }

    public function store(StoreSpeakerRequest $request)
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
        $data = $this->sanitizeSpeakerData($data);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $speaker = Speaker::create($data);

        if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
            $speaker->addMedia($photoPath)->toMediaCollection('photo');
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.speakers.index');
    }

    public function edit(Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.speakers.edit', compact('speaker'));
    }

    public function update(UpdateSpeakerRequest $request, Speaker $speaker)
    {
        $speaker->update($this->sanitizeSpeakerData($request->only([
            'name',
            'role',
            'company',
            'twitter',
            'facebook',
            'linkedin',
            'description',
            'full_description',
        ])));

        if ($request->input('photo', false)) {
            if (!$speaker->photo || $request->input('photo') !== $speaker->photo->file_name) {
                if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
                    $speaker->addMedia($photoPath)->toMediaCollection('photo');
                }
            }
        } elseif ($speaker->photo) {
            $speaker->photo->delete();
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $speaker->event_id)
            : redirect()->route('admin.speakers.index');
    }

    public function show(Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.speakers.show', compact('speaker'));
    }

    public function destroy(Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speaker->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $speaker->event_id)
            : back();
    }

    public function massDestroy(MassDestroySpeakerRequest $request)
    {
        Speaker::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods (used from events/{event}/edit tab) ─────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('speaker_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'role'             => ['nullable', 'string', 'max:255'],
            'company'          => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'twitter'          => ['nullable', 'url'],
            'facebook'         => ['nullable', 'url'],
            'linkedin'         => ['nullable', 'url'],
            'photo'            => ['nullable', 'string'],
        ]);

        $data = $this->sanitizeSpeakerData($data);
        $data['event_id'] = $event->id;

        $speaker = Speaker::create($data);

        if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
            $speaker->addMedia($photoPath)->toMediaCollection('photo');
        }

        $speaker->refresh();
        $speaker->load([]);

        return response()->json([
            'id'       => $speaker->id,
            'name'     => $speaker->name,
            'role'     => $speaker->role,
            'company'  => $speaker->company,
            'photo'    => $speaker->photo ? ['url' => $speaker->photo->url, 'thumbnail' => $speaker->photo->thumbnail] : null,
        ]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($speaker->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'role'             => ['nullable', 'string', 'max:255'],
            'company'          => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'twitter'          => ['nullable', 'url'],
            'facebook'         => ['nullable', 'url'],
            'linkedin'         => ['nullable', 'url'],
            'photo'            => ['nullable', 'string'],
        ]);

        $speaker->update($this->sanitizeSpeakerData($data));

        if ($request->input('photo', false)) {
            if (!$speaker->photo || $request->input('photo') !== $speaker->photo->file_name) {
                if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
                    $speaker->addMedia($photoPath)->toMediaCollection('photo');
                }
            }
        } elseif ($request->has('photo') && !$request->input('photo') && $speaker->photo) {
            $speaker->photo->delete();
        }

        $speaker->refresh();

        return response()->json([
            'id'      => $speaker->id,
            'name'    => $speaker->name,
            'role'    => $speaker->role,
            'company' => $speaker->company,
            'photo'   => $speaker->photo ? ['url' => $speaker->photo->url, 'thumbnail' => $speaker->photo->thumbnail] : null,
        ]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($speaker->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speaker->delete();

        return response()->json(['ok' => true]);
    }

    private function sanitizeSpeakerData(array $data)
    {
        foreach (['twitter', 'facebook', 'linkedin'] as $field) {
            $data[$field] = safe_href($data[$field] ?? null);
        }

        foreach (['description', 'full_description'] as $field) {
            $data[$field] = clean_html($data[$field] ?? null);
        }

        return $data;
    }
}
