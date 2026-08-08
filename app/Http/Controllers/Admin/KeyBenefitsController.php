<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyKeyBenefitRequest;
use App\Http\Requests\StoreKeyBenefitRequest;
use App\Http\Requests\UpdateKeyBenefitRequest;
use App\KeyBenefit;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KeyBenefitsController extends Controller
{
    use MediaUploadingTrait;
    public function index()
    {
        abort_if(Gate::denies('key_benefit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $keyBenefits = KeyBenefit::where('event_id', current_event_id())->orderBy('sort_order')->get();

        return view('admin.key-benefits.index', compact('keyBenefits'));
    }

    public function create()
    {
        abort_if(Gate::denies('key_benefit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.key-benefits.create');
    }

    public function store(StoreKeyBenefitRequest $request)
    {
        $data = $request->only(['icon', 'title', 'description', 'sort_order']);
        $data['description'] = clean_html($data['description'] ?? null);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $data['sort_order'] = $request->input('sort_order', 0);

        $iconPath = $this->tmpUploadPath($request->input('icon'));
        if ($iconPath) {
            $data['icon'] = null;
            $keyBenefit = KeyBenefit::create($data);
            $keyBenefit->addMedia($iconPath)->toMediaCollection('icon');
        } else {
            $keyBenefit = KeyBenefit::create($data);
        }

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.key-benefits.index');
    }

    public function edit(KeyBenefit $keyBenefit)
    {
        abort_if(Gate::denies('key_benefit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.key-benefits.edit', compact('keyBenefit'));
    }

    public function update(UpdateKeyBenefitRequest $request, KeyBenefit $keyBenefit)
    {
        $data = $request->only(['icon', 'title', 'description', 'sort_order']);
        $data['description'] = clean_html($data['description'] ?? null);

        $iconPath = $this->tmpUploadPath($request->input('icon'));
        if ($iconPath) {
            $data['icon'] = null;
            $keyBenefit->clearMediaCollection('icon');
            $keyBenefit->addMedia($iconPath)->toMediaCollection('icon');
        }

        $keyBenefit->update($data);

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $keyBenefit->event_id)
            : redirect()->route('admin.key-benefits.index');
    }

    public function show(KeyBenefit $keyBenefit)
    {
        abort_if(Gate::denies('key_benefit_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.key-benefits.show', compact('keyBenefit'));
    }

    public function destroy(KeyBenefit $keyBenefit)
    {
        abort_if(Gate::denies('key_benefit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $keyBenefit->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $keyBenefit->event_id)
            : back();
    }

    public function massDestroy(MassDestroyKeyBenefitRequest $request)
    {
        KeyBenefit::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('key_benefit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'icon'        => ['nullable', 'string', 'max:100'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $data['event_id']   = $event->id;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['description'] = clean_html($data['description'] ?? null);

        $kb = KeyBenefit::create($data);

        if ($iconPath = $this->tmpUploadPath($request->input('icon'))) {
            $kb->addMedia($iconPath)->toMediaCollection('icon');
        }

        return response()->json(['id' => $kb->id, 'icon' => $kb->icon, 'title' => $kb->title, 'sort_order' => $kb->sort_order]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, KeyBenefit $keyBenefit)
    {
        abort_if(Gate::denies('key_benefit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($keyBenefit->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'icon'        => ['nullable', 'string', 'max:100'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $data['description'] = clean_html($data['description'] ?? null);
        $keyBenefit->update($data);

        if ($iconPath = $this->tmpUploadPath($request->input('icon'))) {
            $keyBenefit->clearMediaCollection('icon');
            $keyBenefit->addMedia($iconPath)->toMediaCollection('icon');
        }

        return response()->json(['id' => $keyBenefit->id, 'icon' => $keyBenefit->icon, 'title' => $keyBenefit->title, 'sort_order' => $keyBenefit->sort_order]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, KeyBenefit $keyBenefit)
    {
        abort_if(Gate::denies('key_benefit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($keyBenefit->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $keyBenefit->delete();

        return response()->json(['ok' => true]);
    }
}
