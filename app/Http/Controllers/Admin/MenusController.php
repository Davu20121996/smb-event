<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMenuRequest;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Menu;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenusController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('menu_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('sort_order')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        abort_if(Gate::denies('menu_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parents = Menu::whereNull('parent_id')->orderBy('sort_order')->get();

        return view('admin.menus.create', compact('parents'));
    }

    public function store(StoreMenuRequest $request)
    {
        $data = $request->only(['label', 'url', 'parent_id', 'sort_order', 'is_active']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->input('sort_order', 0);
        Menu::create($data);

        return redirect()->route('admin.menus.index');
    }

    public function edit(Menu $menu)
    {
        abort_if(Gate::denies('menu_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parents = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('sort_order')->get();

        return view('admin.menus.edit', compact('menu', 'parents'));
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->only(['label', 'url', 'parent_id', 'sort_order', 'is_active']);
        $data['is_active'] = $request->has('is_active');
        Menu::where('id', $menu->id)->update($data);

        return redirect()->route('admin.menus.index');
    }

    public function show(Menu $menu)
    {
        abort_if(Gate::denies('menu_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.menus.show', compact('menu'));
    }

    public function destroy(Menu $menu)
    {
        abort_if(Gate::denies('menu_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $menu->delete();

        return back();
    }

    public function massDestroy(MassDestroyMenuRequest $request)
    {
        Menu::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
