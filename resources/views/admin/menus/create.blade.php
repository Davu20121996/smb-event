@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.menu.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.menus.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.partials.multilang_hint', ['mlFields' => ['label'], 'isCreate' => true])
            <div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
                <label for="label">{{ trans('cruds.menu.fields.label') }}*</label>
                <input type="text" id="label" name="label" class="form-control" value="{{ old('label', isset($menu) ? $menu->label : '') }}" required>
                @if($errors->has('label'))
                    <p class="help-block">
                        {{ $errors->first('label') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.menu.fields.label_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                <label for="url">{{ trans('cruds.menu.fields.url') }}</label>
                <input type="text" id="url" name="url" class="form-control" value="{{ old('url', isset($menu) ? $menu->url : '') }}">
                @if($errors->has('url'))
                    <p class="help-block">
                        {{ $errors->first('url') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.menu.fields.url_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                <label for="parent_id">{{ trans('cruds.menu.fields.parent') }}</label>
                <select class="form-control" name="parent_id" id="parent_id">
                    <option value="">{{ trans('global.none') }}</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', isset($menu) ? $menu->parent_id : '') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->label }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('parent_id'))
                    <p class="help-block">
                        {{ $errors->first('parent_id') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.menu.fields.parent_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('sort_order') ? 'has-error' : '' }}">
                <label for="sort_order">{{ trans('cruds.menu.fields.sort_order') }}</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', isset($menu) ? $menu->sort_order : 0) }}">
                @if($errors->has('sort_order'))
                    <p class="help-block">
                        {{ $errors->first('sort_order') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.menu.fields.sort_order_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('is_active') ? 'has-error' : '' }}">
                <label for="is_active">{{ trans('cruds.menu.fields.is_active') }}</label>
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', isset($menu) ? $menu->is_active : 1) ? 'checked' : '' }}>
                @if($errors->has('is_active'))
                    <p class="help-block">
                        {{ $errors->first('is_active') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.menu.fields.is_active_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection
