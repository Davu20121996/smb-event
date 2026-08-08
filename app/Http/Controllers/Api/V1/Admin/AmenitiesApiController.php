<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Amenity;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Http\Resources\Admin\AmenityResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AmenitiesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('amenity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return AmenityResource::collection(Amenity::where('event_id', current_event_id())->get());
    }

    public function store(StoreAmenityRequest $request)
    {
        $data = $request->only(['name']);
        $data['event_id'] = current_event_id();
        $amenity = Amenity::create($data);

        return (new AmenityResource($amenity))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Amenity $amenity)
    {
        abort_if(Gate::denies('amenity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $amenity->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new AmenityResource($amenity);
    }

    public function update(UpdateAmenityRequest $request, Amenity $amenity)
    {
        abort_if((int) $amenity->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $amenity->update($request->only(['name']));

        return (new AmenityResource($amenity))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Amenity $amenity)
    {
        abort_if(Gate::denies('amenity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $amenity->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $amenity->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
