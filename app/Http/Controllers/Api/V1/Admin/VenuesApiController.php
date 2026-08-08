<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreVenueRequest;
use App\Http\Requests\UpdateVenueRequest;
use App\Http\Resources\Admin\VenueResource;
use App\Venue;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VenuesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('venue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return VenueResource::collection(Venue::where('event_id', current_event_id())->get());
    }

    public function store(StoreVenueRequest $request)
    {
        $data = $request->only(['name', 'address', 'latitude', 'longitude', 'description']);
        $data['event_id'] = current_event_id();
        $venue = Venue::create($data);

        if ($photoPath = $this->tmpUploadPath($request->input('photos'))) {
            $venue->addMedia($photoPath)->toMediaCollection('photos');
        }

        return (new VenueResource($venue))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Venue $venue)
    {
        abort_if(Gate::denies('venue_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $venue->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new VenueResource($venue);
    }

    public function update(UpdateVenueRequest $request, Venue $venue)
    {
        abort_if((int) $venue->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $venue->update($request->only(['name', 'address', 'latitude', 'longitude', 'description']));

        if ($request->input('photos', false)) {
            if (!$venue->photos || $request->input('photos') !== $venue->photos->file_name) {
                if ($photoPath = $this->tmpUploadPath($request->input('photos'))) {
                    $venue->addMedia($photoPath)->toMediaCollection('photos');
                }
            }
        } elseif ($venue->photos) {
            $venue->photos->delete();
        }

        return (new VenueResource($venue))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Venue $venue)
    {
        abort_if(Gate::denies('venue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $venue->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $venue->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
