<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Http\Resources\Admin\HotelResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return HotelResource::collection(Hotel::where('event_id', current_event_id())->get());
    }

    public function store(StoreHotelRequest $request)
    {
        $data = $request->only(['name', 'rating', 'address', 'description']);
        $data['event_id'] = current_event_id();
        $hotel = Hotel::create($data);

        if ($photoPath = $this->tmpUploadPath($request->input('photo'))) {
            $hotel->addMedia($photoPath)->toMediaCollection('photo');
        }

        return (new HotelResource($hotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Hotel $hotel)
    {
        abort_if(Gate::denies('hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $hotel->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new HotelResource($hotel);
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        abort_if((int) $hotel->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

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

        return (new HotelResource($hotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Hotel $hotel)
    {
        abort_if(Gate::denies('hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $hotel->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $hotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
