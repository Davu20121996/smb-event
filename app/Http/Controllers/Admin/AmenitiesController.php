<?php

namespace App\Http\Controllers\Admin;

use App\Amenity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAmenityRequest;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AmenitiesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('amenity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $amenities = Amenity::where('event_id', current_event_id())->get();

        return view('admin.amenities.index', compact('amenities'));
    }

    public function create()
    {
        abort_if(Gate::denies('amenity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.amenities.create');
    }

    public function store(StoreAmenityRequest $request)
    {
        $data = $request->only(['name']);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $amenity = Amenity::create($data);

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.amenities.index');
    }

    public function edit(Amenity $amenity)
    {
        abort_if(Gate::denies('amenity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.amenities.edit', compact('amenity'));
    }

    public function update(UpdateAmenityRequest $request, Amenity $amenity)
    {
        $amenity->update($request->only(['name']));

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $amenity->event_id)
            : redirect()->route('admin.amenities.index');
    }

    public function show(Amenity $amenity)
    {
        abort_if(Gate::denies('amenity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.amenities.show', compact('amenity'));
    }

    public function destroy(Amenity $amenity)
    {
        abort_if(Gate::denies('amenity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $amenity->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $amenity->event_id)
            : back();
    }

    public function massDestroy(MassDestroyAmenityRequest $request)
    {
        Amenity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('amenity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate(['name' => ['required', 'string', 'max:255']]);
        $data['event_id'] = $event->id;
        $amenity = Amenity::create($data);

        return response()->json(['id' => $amenity->id, 'name' => $amenity->name]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Amenity $amenity)
    {
        abort_if(Gate::denies('amenity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($amenity->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate(['name' => ['required', 'string', 'max:255']]);
        $amenity->update($data);

        return response()->json(['id' => $amenity->id, 'name' => $amenity->name]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Amenity $amenity)
    {
        abort_if(Gate::denies('amenity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($amenity->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $amenity->delete();

        return response()->json(['ok' => true]);
    }
}
