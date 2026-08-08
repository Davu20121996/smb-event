<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySponsorRequest;
use App\Http\Requests\StoreSponsorRequest;
use App\Http\Requests\UpdateSponsorRequest;
use App\Sponsor;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class SponsorsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('sponsor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponsors = Sponsor::where('event_id', current_event_id())->get();

        return view('admin.sponsors.index', compact('sponsors'));
    }

    public function create()
    {
        abort_if(Gate::denies('sponsor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sponsors.create');
    }

    public function store(StoreSponsorRequest $request)
    {
        $data = $request->only(['name', 'link']);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $data['link'] = safe_href($data['link'] ?? null);
        $sponsor = Sponsor::create($data);

        if ($logoPath = $this->tmpUploadPath($request->input('logo'))) {
            $sponsor->addMedia($logoPath)->toMediaCollection('logo');
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.sponsors.index');
    }

    public function edit(Sponsor $sponsor)
    {
        abort_if(Gate::denies('sponsor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sponsors.edit', compact('sponsor'));
    }

    public function update(UpdateSponsorRequest $request, Sponsor $sponsor)
    {
        $data = $request->only(['name', 'link']);
        $data['link'] = safe_href($data['link'] ?? null);
        $sponsor->update($data);

        if ($request->input('logo', false)) {
            if (!$sponsor->logo || $request->input('logo') !== $sponsor->logo->file_name) {
                if ($logoPath = $this->tmpUploadPath($request->input('logo'))) {
                    $sponsor->addMedia($logoPath)->toMediaCollection('logo');
                }
            }
        } elseif ($sponsor->logo) {
            $sponsor->logo->delete();
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $sponsor->event_id)
            : redirect()->route('admin.sponsors.index');
    }

    public function show(Sponsor $sponsor)
    {
        abort_if(Gate::denies('sponsor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sponsors.show', compact('sponsor'));
    }

    public function destroy(Sponsor $sponsor)
    {
        abort_if(Gate::denies('sponsor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponsor->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $sponsor->event_id)
            : back();
    }

    public function massDestroy(MassDestroySponsorRequest $request)
    {
        Sponsor::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('sponsor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'link'  => ['nullable', 'url'],
            'logo'  => ['nullable', 'string'],
        ]);

        $data['event_id'] = $event->id;
        $data['link'] = safe_href($data['link'] ?? null);

        $sponsor = Sponsor::create(\Arr::only($data, ['name', 'link', 'event_id']));

        if ($logoPath = $this->tmpUploadPath($request->input('logo'))) {
            $sponsor->addMedia($logoPath)->toMediaCollection('logo');
        }

        $sponsor->refresh();

        return response()->json([
            'id'   => $sponsor->id,
            'name' => $sponsor->name,
            'link' => $sponsor->link,
            'logo' => $sponsor->logo ? ['url' => $sponsor->logo->url, 'thumbnail' => $sponsor->logo->thumbnail] : null,
        ]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Sponsor $sponsor)
    {
        abort_if(Gate::denies('sponsor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($sponsor->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'link'  => ['nullable', 'url'],
            'logo'  => ['nullable', 'string'],
        ]);

        $data['link'] = safe_href($data['link'] ?? null);
        $sponsor->update(\Arr::only($data, ['name', 'link']));

        if ($request->input('logo', false)) {
            if (!$sponsor->logo || $request->input('logo') !== $sponsor->logo->file_name) {
                if ($logoPath = $this->tmpUploadPath($request->input('logo'))) {
                    $sponsor->addMedia($logoPath)->toMediaCollection('logo');
                }
            }
        } elseif ($request->has('logo') && !$request->input('logo') && $sponsor->logo) {
            $sponsor->logo->delete();
        }

        $sponsor->refresh();

        return response()->json([
            'id'   => $sponsor->id,
            'name' => $sponsor->name,
            'link' => $sponsor->link,
            'logo' => $sponsor->logo ? ['url' => $sponsor->logo->url, 'thumbnail' => $sponsor->logo->thumbnail] : null,
        ]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Sponsor $sponsor)
    {
        abort_if(Gate::denies('sponsor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($sponsor->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponsor->delete();

        return response()->json(['ok' => true]);
    }
}
