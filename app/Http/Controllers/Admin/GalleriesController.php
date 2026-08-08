<?php

namespace App\Http\Controllers\Admin;

use App\Gallery;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyGalleryRequest;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class GalleriesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('gallery_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $galleries = Gallery::where('event_id', current_event_id())->get();

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        abort_if(Gate::denies('gallery_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.galleries.create');
    }

    public function store(StoreGalleryRequest $request)
    {
        $data = $request->only(['name']);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $gallery = Gallery::create($data);

        foreach ($request->input('photos', []) as $file) {
            if ($photoPath = $this->tmpUploadPath($file)) {
                $gallery->addMedia($photoPath)->toMediaCollection('photos');
            }
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.galleries.index');
    }

    public function edit(Gallery $gallery)
    {
        abort_if(Gate::denies('gallery_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        $gallery->update($request->only(['name']));

        if (count($gallery->photos) > 0) {
            foreach ($gallery->photos as $media) {
                if (!in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }

        $media = $gallery->photos->pluck('file_name')->toArray();

        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                if ($photoPath = $this->tmpUploadPath($file)) {
                    $gallery->addMedia($photoPath)->toMediaCollection('photos');
                }
            }
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $gallery->event_id)
            : redirect()->route('admin.galleries.index');
    }

    public function show(Gallery $gallery)
    {
        abort_if(Gate::denies('gallery_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.galleries.show', compact('gallery'));
    }

    public function destroy(Gallery $gallery)
    {
        abort_if(Gate::denies('gallery_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gallery->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $gallery->event_id)
            : back();
    }

    public function massDestroy(MassDestroyGalleryRequest $request)
    {
        Gallery::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('gallery_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'photos'   => ['nullable', 'array'],
            'photos.*' => ['string'],
        ]);

        $data['event_id'] = $event->id;
        $gallery = Gallery::create(\Arr::only($data, ['name', 'event_id']));

        foreach ($request->input('photos', []) as $file) {
            if ($photoPath = $this->tmpUploadPath($file)) {
                $gallery->addMedia($photoPath)->toMediaCollection('photos');
            }
        }

        $gallery->refresh();
        $thumbs = $gallery->photos->map(fn($p) => ['url' => $p->url, 'thumbnail' => $p->thumbnail, 'file_name' => $p->file_name])->values();

        return response()->json(['id' => $gallery->id, 'name' => $gallery->name, 'photos' => $thumbs]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Gallery $gallery)
    {
        abort_if(Gate::denies('gallery_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($gallery->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'photos'   => ['nullable', 'array'],
            'photos.*' => ['string'],
        ]);

        $gallery->update(['name' => $data['name']]);

        $keepFiles = $request->input('photos', []);

        if (count($gallery->photos) > 0) {
            foreach ($gallery->photos as $media) {
                if (!in_array($media->file_name, $keepFiles)) {
                    $media->delete();
                }
            }
        }

        $media = $gallery->photos->pluck('file_name')->toArray();

        foreach ($keepFiles as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                if ($photoPath = $this->tmpUploadPath($file)) {
                    $gallery->addMedia($photoPath)->toMediaCollection('photos');
                }
            }
        }

        $gallery->refresh();
        $thumbs = $gallery->photos->map(fn($p) => ['url' => $p->url, 'thumbnail' => $p->thumbnail, 'file_name' => $p->file_name])->values();

        return response()->json(['id' => $gallery->id, 'name' => $gallery->name, 'photos' => $thumbs]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Gallery $gallery)
    {
        abort_if(Gate::denies('gallery_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($gallery->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gallery->delete();

        return response()->json(['ok' => true]);
    }
}
