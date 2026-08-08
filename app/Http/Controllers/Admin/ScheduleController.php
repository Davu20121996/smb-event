<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyScheduleRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Schedule;
use App\Speaker;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('schedule_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $schedules = Schedule::where('event_id', current_event_id())->get();

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('schedule_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventId = (int) $request->input('event_id', current_event_id());
        $speakers = Speaker::where('event_id', $eventId)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.schedules.create', compact('speakers'));
    }

    public function store(StoreScheduleRequest $request)
    {
        $data = $request->only(['title', 'subtitle', 'day_number', 'start_time', 'speaker_id', 'desc']);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $schedule = Schedule::create($data);

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.schedules.index');
    }

    public function edit(Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speakers = Speaker::where('event_id', $schedule->event_id)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $schedule->load('speaker');

        return view('admin.schedules.edit', compact('speakers', 'schedule'));
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->only(['title', 'subtitle', 'day_number', 'start_time', 'speaker_id', 'desc']));

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $schedule->event_id)
            : redirect()->route('admin.schedules.index');
    }

    public function show(Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $schedule->load('speaker');

        return view('admin.schedules.show', compact('schedule'));
    }

    public function destroy(Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $schedule->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $schedule->event_id)
            : back();
    }

    public function massDestroy(MassDestroyScheduleRequest $request)
    {
        Schedule::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('schedule_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'subtitle'   => ['nullable', 'string', 'max:255'],
            'day_number' => ['nullable', 'integer', 'min:1'],
            'start_time' => ['required', 'string'],
            'speaker_id' => ['nullable', 'integer', 'exists:speakers,id'],
            'desc'       => ['nullable', 'string'],
        ]);

        $data['event_id'] = $event->id;
        $schedule = Schedule::create($data);
        $schedule->load('speaker');

        return response()->json([
            'id'         => $schedule->id,
            'title'      => $schedule->title,
            'subtitle'   => $schedule->subtitle,
            'day_number' => $schedule->day_number,
            'start_time' => $schedule->start_time,
            'speaker'    => $schedule->speaker ? $schedule->speaker->name : null,
            'speaker_id' => $schedule->speaker_id,
        ]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($schedule->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'subtitle'   => ['nullable', 'string', 'max:255'],
            'day_number' => ['nullable', 'integer', 'min:1'],
            'start_time' => ['required', 'string'],
            'speaker_id' => ['nullable', 'integer', 'exists:speakers,id'],
            'desc'       => ['nullable', 'string'],
        ]);

        $schedule->update($data);
        $schedule->load('speaker');

        return response()->json([
            'id'         => $schedule->id,
            'title'      => $schedule->title,
            'subtitle'   => $schedule->subtitle,
            'day_number' => $schedule->day_number,
            'start_time' => $schedule->start_time,
            'speaker'    => $schedule->speaker ? $schedule->speaker->name : null,
            'speaker_id' => $schedule->speaker_id,
        ]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($schedule->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $schedule->delete();

        return response()->json(['ok' => true]);
    }
}
