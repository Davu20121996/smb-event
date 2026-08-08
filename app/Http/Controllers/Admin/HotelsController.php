<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyHotelRequest;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class HotelsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Hotel::where('event_id', current_event_id())->get();

        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        abort_if(Gate::denies('hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.hotels.create');
    }

    public function store(StoreHotelRequest $request)
    {
        $data = $request->only(['name', 'rating', 'address', 'description']);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $hotel = Hotel::create($data);

        if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
            $hotel->addMedia($photoPath)->toMediaCollection('photo');
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.hotels.index');
    }

    public function edit(Hotel $hotel)
    {
        abort_if(Gate::denies('hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $hotel->update($request->only(['name', 'rating', 'address', 'description']));

        if ($request->input('photo', false)) {
            if (!$hotel->photo || $request->input('photo') !== $hotel->photo->file_name) {
                if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
                    $hotel->addMedia($photoPath)->toMediaCollection('photo');
                }
            }
        } elseif ($hotel->photo) {
            $hotel->photo->delete();
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $hotel->event_id)
            : redirect()->route('admin.hotels.index');
    }

    public function show(Hotel $hotel)
    {
        abort_if(Gate::denies('hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.hotels.show', compact('hotel'));
    }

    public function destroy(Hotel $hotel)
    {
        abort_if(Gate::denies('hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotel->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $hotel->event_id)
            : back();
    }

    public function massDestroy(MassDestroyHotelRequest $request)
    {
        Hotel::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'address'     => ['nullable', 'string', 'max:500'],
            'rating'      => ['nullable', 'integer', 'min:1', 'max:5'],
            'description' => ['nullable', 'string'],
            'photo'       => ['nullable', 'string'],
        ]);

        $data['event_id'] = $event->id;
        $hotel = Hotel::create(\Arr::only($data, ['name', 'address', 'rating', 'description', 'event_id']));

        if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
            $hotel->addMedia($photoPath)->toMediaCollection('photo');
        }

        $hotel->refresh();

        return response()->json([
            'id'      => $hotel->id,
            'name'    => $hotel->name,
            'address' => $hotel->address,
            'rating'  => $hotel->rating,
            'photo'   => $hotel->photo ? ['url' => $hotel->photo->url, 'thumbnail' => $hotel->photo->thumbnail] : null,
        ]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Hotel $hotel)
    {
        abort_if(Gate::denies('hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($hotel->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'address'     => ['nullable', 'string', 'max:500'],
            'rating'      => ['nullable', 'integer', 'min:1', 'max:5'],
            'description' => ['nullable', 'string'],
            'photo'       => ['nullable', 'string'],
        ]);

        $hotel->update(\Arr::only($data, ['name', 'address', 'rating', 'description']));

        if ($request->input('photo', false)) {
            if (!$hotel->photo || $request->input('photo') !== $hotel->photo->file_name) {
                if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
                    $hotel->addMedia($photoPath)->toMediaCollection('photo');
                }
            }
        } elseif ($request->has('photo') && !$request->input('photo') && $hotel->photo) {
            $hotel->photo->delete();
        }

        $hotel->refresh();

        return response()->json([
            'id'      => $hotel->id,
            'name'    => $hotel->name,
            'address' => $hotel->address,
            'rating'  => $hotel->rating,
            'photo'   => $hotel->photo ? ['url' => $hotel->photo->url, 'thumbnail' => $hotel->photo->thumbnail] : null,
        ]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Hotel $hotel)
    {
        abort_if(Gate::denies('hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($hotel->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotel->delete();

        return response()->json(['ok' => true]);
    }
}
