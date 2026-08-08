<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\Admin\ScheduleResource;
use App\Schedule;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScheduleApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('schedule_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return ScheduleResource::collection(Schedule::with(['speaker'])->where('event_id', current_event_id())->get());
    }

    public function store(StoreScheduleRequest $request)
    {
        $data = $request->only(['title', 'subtitle', 'day_number', 'start_time', 'speaker_id', 'desc']);
        $data['event_id'] = current_event_id();
        $schedule = Schedule::create($data);

        return (new ScheduleResource($schedule))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $schedule->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        return new ScheduleResource($schedule->load(['speaker']));
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        abort_if((int) $schedule->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $schedule->update($request->only(['title', 'subtitle', 'day_number', 'start_time', 'speaker_id', 'desc']));

        return (new ScheduleResource($schedule))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if((int) $schedule->event_id !== (int) current_event_id(), Response::HTTP_NOT_FOUND);

        $schedule->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
