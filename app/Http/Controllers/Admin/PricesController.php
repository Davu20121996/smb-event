<?php

namespace App\Http\Controllers\Admin;

use App\Amenity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPriceRequest;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Price;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class PricesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('price_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prices = Price::where('event_id', current_event_id())->get();

        return view('admin.prices.index', compact('prices'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('price_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventId = (int) $request->input('event_id', current_event_id());
        $amenities = Amenity::where('event_id', $eventId)->pluck('name', 'id');

        return view('admin.prices.create', compact('amenities'));
    }

    public function store(StorePriceRequest $request)
    {
        $data = $request->only(['name', 'price']);
        $data['event_id'] = $request->input('event_id', current_event_id());
        $price = Price::create($data);
        $price->amenities()->sync($request->input('amenities', []));

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $data['event_id'])
            : redirect()->route('admin.prices.index');
    }

    public function edit(Price $price)
    {
        abort_if(Gate::denies('price_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $amenities = Amenity::where('event_id', $price->event_id)->pluck('name', 'id');

        $price->load('amenities');

        return view('admin.prices.edit', compact('amenities', 'price'));
    }

    public function update(UpdatePriceRequest $request, Price $price)
    {
        $price->update($request->only(['name', 'price']));
        $price->amenities()->sync($request->input('amenities', []));

        return $request->has('event_id')
            ? redirect()->route('admin.events.edit', $price->event_id)
            : redirect()->route('admin.prices.index');
    }

    public function show(Price $price)
    {
        abort_if(Gate::denies('price_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price->load('amenities');

        return view('admin.prices.show', compact('price'));
    }

    public function destroy(Price $price)
    {
        abort_if(Gate::denies('price_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price->delete();

        return request()->has('event_id')
            ? redirect()->route('admin.events.edit', $price->event_id)
            : back();
    }

    public function massDestroy(MassDestroyPriceRequest $request)
    {
        Price::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ── Inline AJAX methods ──────────────────────────────────────────────────

    public function inlineStore(Request $request, \App\Event $event)
    {
        abort_if(Gate::denies('price_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'price'     => ['required', 'numeric', 'min:0'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
        ]);

        $data['event_id'] = $event->id;
        $price = Price::create(\Arr::only($data, ['name', 'price', 'event_id']));
        $price->amenities()->sync($data['amenities'] ?? []);

        return response()->json([
            'id'        => $price->id,
            'name'      => $price->name,
            'price'     => $price->price,
            'amenities' => $price->amenities->pluck('name')->join(', '),
        ]);
    }

    public function inlineUpdate(Request $request, \App\Event $event, Price $price)
    {
        abort_if(Gate::denies('price_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($price->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'price'     => ['required', 'numeric', 'min:0'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
        ]);

        $price->update(\Arr::only($data, ['name', 'price']));
        $price->amenities()->sync($data['amenities'] ?? []);

        return response()->json([
            'id'        => $price->id,
            'name'      => $price->name,
            'price'     => $price->price,
            'amenities' => $price->amenities->pluck('name')->join(', '),
        ]);
    }

    public function inlineDestroy(Request $request, \App\Event $event, Price $price)
    {
        abort_if(Gate::denies('price_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($price->event_id !== $event->id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price->delete();

        return response()->json(['ok' => true]);
    }
}
