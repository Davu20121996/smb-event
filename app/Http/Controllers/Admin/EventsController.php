<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EventsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('event_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = Event::withCount('speakers')->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $event = Event::create($this->fillData($request));

        $this->handleBackgroundImages($request, $event);

        // After creating, redirect to edit so user can add child records
        return redirect()->route('admin.events.edit', $event->id)
            ->with('message', 'Sự kiện đã được tạo. Bạn có thể thêm thông tin chi tiết bên dưới.');
    }

    public function edit(Event $event)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->load([
            'speakers',
            'schedules.speaker',
            'keyBenefits',
            'venues',
            'hotels',
            'galleries',
            'sponsors',
            'faqs',
            'amenities',
            'prices.amenities',
        ]);

        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($this->fillData($request, $event));

        $this->handleBackgroundImages($request, $event);

        return redirect()->route('admin.events.edit', $event->id)
            ->with('message', 'Đã lưu thay đổi.');
    }

    public function show(Event $event)
    {
        abort_if(Gate::denies('event_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->load('speakers', 'schedules', 'venues', 'hotels', 'galleries', 'sponsors', 'faqs', 'amenities', 'prices');

        return view('admin.events.show', compact('event'));
    }

    public function destroy(Event $event)
    {
        abort_if(Gate::denies('event_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->delete();

        return back();
    }

    public function massDestroy(MassDestroyEventRequest $request)
    {
        Event::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function switchEvent(Request $request)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
        ]);

        $eventId = (int) $request->input('event_id');

        session(['current_event_id' => $eventId]);

        Event::whereKey($eventId)->update(['is_active' => 1]);
        Event::whereKeyNot([$eventId])->update(['is_active' => 0]);

        return back();
    }

    private function fillData(Request $request, ?Event $event = null): array
    {
        $data = $request->only([
            'name',
            'slug',
            'description',
            'start_date',
            'end_date',
            'is_active',
            'about_description',
            'about_where',
            'about_when',
            'countdown_enabled',
            'registration_deadline',
            'meta_title',
            'meta_description',
            'favicon_url',
            'og_image',
            'calendar_enabled',
            'zalo_url',
            'fanpage_url',
            'show_gallery',
            'show_sponsors',
            'show_tickets',
        ]);

        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name'], $event);

        if (empty($data['registration_deadline'])) {
            $data['registration_deadline'] = null;
        }

        foreach (['is_active', 'countdown_enabled', 'calendar_enabled', 'show_gallery', 'show_sponsors', 'show_tickets'] as $boolField) {
            $data[$boolField] = $request->has($boolField) ? (int) $request->input($boolField) : 0;
        }

        $data['zalo_url']    = safe_href($data['zalo_url'] ?? null);
        $data['fanpage_url'] = safe_href($data['fanpage_url'] ?? null);

        foreach (['description', 'about_description', 'about_where', 'about_when'] as $htmlField) {
            $data[$htmlField] = clean_html($data[$htmlField] ?? null);
        }

        return $data;
    }

    /**
     * Keep public event URLs unique even when an editor leaves the slug blank
     * or reuses an existing slug. This prevents a database exception on save.
     */
    private function uniqueSlug(?string $value, ?Event $event = null): string
    {
        $base = Str::slug((string) $value) ?: 'event';
        $slug = $base;
        $suffix = 2;

        while (Event::query()
            ->where('slug', $slug)
            ->when($event, fn ($query) => $query->whereKeyNot($event->getKey()))
            ->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    private function handleBackgroundImages(Request $request, Event $event)
    {
        foreach (['mobile_bg_image' => 'mobile_bg', 'pc_bg_image' => 'pc_bg'] as $inputName => $collectionName) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);

                if ($file->getSize() > 5 * 1024 * 1024) {
                    continue;
                }

                $existingMedia = $event->getMedia($collectionName)->first();
                if ($existingMedia) {
                    $existingMedia->delete();
                }

                $event->addMediaFromRequest($inputName)->toMediaCollection($collectionName);
            }
        }
    }

    public function removeBackgroundImage(Request $request, Event $event)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collection = $request->input('collection');

        if (!in_array($collection, ['mobile_bg', 'pc_bg'])) {
            return response()->json(['error' => 'Invalid collection'], 422);
        }

        $media = $event->getMedia($collection)->first();
        if ($media) {
            $media->delete();
        }

        return response()->json(['success' => true]);
    }
}
