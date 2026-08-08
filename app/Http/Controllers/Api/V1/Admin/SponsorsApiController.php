<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSponsorRequest;
use App\Http\Requests\UpdateSponsorRequest;
use App\Http\Resources\Admin\SponsorResource;
use App\Sponsor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SponsorsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('sponsor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return SponsorResource::collection(Sponsor::where('event_id', current_event_id())->get());
    }

    public function store(StoreSponsorRequest $request)
    {
        $data = $request->only(['name', 'link']);
        $data['event_id'] = current_event_id();
        $data['link'] = safe_href($data['link'] ?? null);
        $sponsor = Sponsor::create($data);

        if ($logoPath = $this->tmpUploadPath($request->input('logo'))) {
            $sponsor->addMedia($logoPath)->toMediaCollection('logo');
        }

        return (new SponsorResource($sponsor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Sponsor $sponsor)
    {
        abort_if(Gate::denies('sponsor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $sponsor->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new SponsorResource($sponsor);
    }

    public function update(UpdateSponsorRequest $request, Sponsor $sponsor)
    {
        abort_if((int) $sponsor->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

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

        return (new SponsorResource($sponsor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Sponsor $sponsor)
    {
        abort_if(Gate::denies('sponsor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $sponsor->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $sponsor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
