<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\Admin\SettingResource;
use App\Setting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = Setting::where(function ($query) {
            $query->whereNull('event_id')->orWhere('event_id', current_event_id());
        })->get();

        return SettingResource::collection($settings);
    }

    public function store(StoreSettingRequest $request)
    {
        $data = $request->only(['key', 'value']);
        $data['event_id'] = current_event_id();
        $setting = Setting::create($data);

        return (new SettingResource($setting))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Setting $setting)
    {
        abort_if(Gate::denies('setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($setting->event_id !== null && (int) $setting->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new SettingResource($setting);
    }

    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        abort_if($setting->event_id !== null && (int) $setting->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $setting->update($request->only(['key', 'value']));

        return (new SettingResource($setting))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Setting $setting)
    {
        abort_if(Gate::denies('setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($setting->event_id !== null && (int) $setting->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $setting->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
