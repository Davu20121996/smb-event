<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyVenueRequest;
use App\Http\Requests\StoreVenueRequest;
use App\Http\Requests\UpdateVenueRequest;
use App\Venue;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class VenuesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('venue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $venues = Venue::where('event_id', current_event_id())->get();

        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        abort_if(Gate::denies('venue_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venues.create');
    }

    public function store(StoreVenueRequest $request)
    {
        $data = $request->only(['name', 'address', 'latitude', 'longitude', 'google_maps_url', 'description']);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $venue = Venue::create($data);

        foreach ($request->input('photos', []) as $file) {
            if ($photoPath = $this->tmpUploadPath($file)) {
                $venue->addMedia($photoPath)->toMediaCollection('photos');
            }
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.venues.index');
    }

    public function edit(Venue $venue)
    {
        abort_if(Gate::denies('venue_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venues.edit', compact('venue'));
    }

    public function update(UpdateVenueRequest $request, Venue $venue)
    {
        $venue->update($request->only(['name', 'address', 'latitude', 'longitude', 'google_maps_url', 'description']));

        if (count($venue->photos) > 0) {
            foreach ($venue->photos as $media) {
                if (!in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }

        $media = $venue->photos->pluck('file_name')->toArray();

        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                if ($photoPath = $this->tmpUploadPath($file)) {
                    $venue->addMedia($photoPath)->toMediaCollection('photos');
                }
            }
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $venue->event_id)
            : redirect()->route('admin.venues.index');
    }

    public function show(Venue $venue)
    {
        abort_if(Gate::denies('venue_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venues.show', compact('venue'));
    }

    public function destroy(Venue $venue)
    {
        abort_if(Gate::denies('venue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $venue->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $venue->event_id)
            : back();
    }

    public function massDestroy(MassDestroyVenueRequest $request)
    {
        Venue::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('venue_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'address'         => ['required', 'string', 'max:500'],
            'latitude'        => ['nullable', 'string', 'max:50'],
            'longitude'       => ['nullable', 'string', 'max:50'],
            'google_maps_url' => ['nullable', 'string', 'max:1000'],
            'description'     => ['nullable', 'string'],
            'photos'          => ['nullable', 'array'],
            'photos.*'        => ['string'],
        ]);

        $data['event_id'] = $event->id;
        $venue = Venue::create(\Arr::only($data, ['name', 'address', 'latitude', 'longitude', 'google_maps_url', 'description', 'event_id']));

        foreach ($request->input('photos', []) as $file) {
            if ($photoPath = $this->tmpUploadPath($file)) {
                $venue->addMedia($photoPath)->toMediaCollection('photos');
            }
        }

        $venue->refresh();

        return response()->json([
            'id'      => $venue->id,
            'name'    => $venue->name,
            'address' => $venue->address,
        ]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Venue $venue)
    {
        abort_if(Gate::denies('venue_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($venue->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'address'         => ['required', 'string', 'max:500'],
            'latitude'        => ['nullable', 'string', 'max:50'],
            'longitude'       => ['nullable', 'string', 'max:50'],
            'google_maps_url' => ['nullable', 'string', 'max:1000'],
            'description'     => ['nullable', 'string'],
            'photos'          => ['nullable', 'array'],
            'photos.*'        => ['string'],
        ]);

        $venue->update(\Arr::only($data, ['name', 'address', 'latitude', 'longitude', 'google_maps_url', 'description']));

        $keepFiles = $request->input('photos', []);

        if (count($venue->photos) > 0) {
            foreach ($venue->photos as $media) {
                if (!in_array($media->file_name, $keepFiles)) {
                    $media->delete();
                }
            }
        }

        $media = $venue->photos->pluck('file_name')->toArray();

        foreach ($keepFiles as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                if ($photoPath = $this->tmpUploadPath($file)) {
                    $venue->addMedia($photoPath)->toMediaCollection('photos');
                }
            }
        }

        return response()->json([
            'id'      => $venue->id,
            'name'    => $venue->name,
            'address' => $venue->address,
        ]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Venue $venue)
    {
        abort_if(Gate::denies('venue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($venue->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $venue->delete();

        return response()->json(['ok' => true]);
    }
}
