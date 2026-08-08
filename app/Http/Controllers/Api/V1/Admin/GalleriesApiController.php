<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Gallery;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\Admin\GalleryResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GalleriesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('gallery_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return GalleryResource::collection(Gallery::where('event_id', current_event_id())->get());
    }

    public function store(StoreGalleryRequest $request)
    {
        $data = $request->only(['name']);
        $data['event_id'] = current_event_id();
        $gallery = Gallery::create($data);

        if ($photoPath = $this->tmpUploadPath($request->input('photos'))) {
            $gallery->addMedia($photoPath)->toMediaCollection('photos');
        }

        return (new GalleryResource($gallery))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Gallery $gallery)
    {
        abort_if(Gate::denies('gallery_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $gallery->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new GalleryResource($gallery);
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        abort_if((int) $gallery->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $gallery->update($request->only(['name']));

        if ($request->input('photos', false)) {
            if (!$gallery->photos || $request->input('photos') !== $gallery->photos->file_name) {
                if ($photoPath = $this->tmpUploadPath($request->input('photos'))) {
                    $gallery->addMedia($photoPath)->toMediaCollection('photos');
                }
            }
        } elseif ($gallery->photos) {
            $gallery->photos->delete();
        }

        return (new GalleryResource($gallery))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Gallery $gallery)
    {
        abort_if(Gate::denies('gallery_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $gallery->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $gallery->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
