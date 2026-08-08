@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.view') }} {{ trans('cruds.menu.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <label>{{ trans('cruds.menu.fields.label') }}</label>
            <p>{{ $menu->label ?? '' }}</p>
        </div>
        <div class="form-group">
            <label>{{ trans('cruds.menu.fields.parent') }}</label>
            <p>{{ $menu->parent->label ?? trans('global.none') }}</p>
        </div>
        <div class="form-group">
            <label>{{ trans('cruds.menu.fields.url') }}</label>
            <p>{{ $menu->url ?? '' }}</p>
        </div>
        <div class="form-group">
            <label>{{ trans('cruds.menu.fields.sort_order') }}</label>
            <p>{{ $menu->sort_order ?? '' }}</p>
        </div>
        <div class="form-group">
            <label>{{ trans('cruds.menu.fields.is_active') }}</label>
            <p>{{ $menu->is_active ? trans('global.yes') : trans('global.no') }}</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.back_to_list') }}
    </div>

    <div class="card-body">
        <a href="{{ route('admin.menus.index') }}" class="btn btn-default">{{ trans('global.back_to_list') }}</a>
    </div>
</div>
@endsection
