<?php

namespace App\Http\Controllers\Admin;

use App\CompanyProfileItem;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CompanyProfileItemsController extends Controller
{
    use MediaUploadingTrait;

    private function authorizeAdmin()
    {
        abort_if(Gate::denies('company_profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function index($section = null)
    {
        $this->authorizeAdmin();

        $section = in_array($section, array_keys(CompanyProfileItem::SECTIONS), true) ? $section : null;
        $sections = CompanyProfileItem::SECTIONS;

        $query = CompanyProfileItem::orderBy('section')->orderBy('sort_order');
        if ($section) {
            $query->where('section', $section);
        }
        $items = $query->get();

        return view('admin.company-profile.items', compact('items', 'sections', 'section'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $this->validateItem($request);

        $item = CompanyProfileItem::create($validated);

        if ($imagePath = $this->tmpUploadPath($request->input('image'))) {
            $item->addMedia($imagePath)->toMediaCollection('image');
        }

        return redirect()->route('admin.company-profile.items', $item->section)
            ->with('message', 'Item added.');
    }

    public function edit(CompanyProfileItem $item)
    {
        $this->authorizeAdmin();

        $sections = CompanyProfileItem::SECTIONS;

        return view('admin.company-profile.item-edit', compact('item', 'sections'));
    }

    public function update(Request $request, CompanyProfileItem $item)
    {
        $this->authorizeAdmin();

        $validated = $this->validateItem($request);

        $item->update($validated);

        if ($request->input('image', false)) {
            if (!$item->image || $request->input('image') !== $item->image->file_name) {
                if ($imagePath = $this->tmpUploadPath($request->input('image'))) {
                    $item->addMedia($imagePath)->toMediaCollection('image');
                }
            }
        } elseif ($item->image) {
            $item->image->delete();
        }

        return redirect()->route('admin.company-profile.items', $item->section)
            ->with('message', 'Item updated.');
    }

    public function destroy(CompanyProfileItem $item)
    {
        $this->authorizeAdmin();

        $item->delete();

        return back()->with('message', 'Item deleted.');
    }

    public function up(CompanyProfileItem $item)
    {
        $this->authorizeAdmin();

        $prev = CompanyProfileItem::where('section', $item->section)
            ->where('sort_order', '<', $item->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($prev) {
            [$item->sort_order, $prev->sort_order] = [$prev->sort_order, $item->sort_order];
            $item->save();
            $prev->save();
        }

        return back();
    }

    public function down(CompanyProfileItem $item)
    {
        $this->authorizeAdmin();

        $next = CompanyProfileItem::where('section', $item->section)
            ->where('sort_order', '>', $item->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            [$item->sort_order, $next->sort_order] = [$next->sort_order, $item->sort_order];
            $item->save();
            $next->save();
        }

        return back();
    }

    private function validateItem(Request $request)
    {
        return $request->validate([
            'section'    => ['required', Rule::in(array_keys(CompanyProfileItem::SECTIONS))],
            'title'      => ['required', 'min:2'],
            'category'   => ['nullable', 'string', 'max:191'],
            'description'=> ['nullable', 'string'],
            'link'       => ['nullable', 'url', 'max:191'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
