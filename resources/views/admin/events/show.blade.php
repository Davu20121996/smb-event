@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.event.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.id') }}
                        </th>
                        <td>
                            {{ $event->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.name') }}
                        </th>
                        <td>
                            {{ $event->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.slug') }}
                        </th>
                        <td>
                            {{ $event->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.description') }}
                        </th>
                        <td>
                            {!! $event->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.start_date') }}
                        </th>
                        <td>
                            {{ $event->start_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.end_date') }}
                        </th>
                        <td>
                            {{ $event->end_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.is_active') }}
                        </th>
                        <td>
                            {{ $event->is_active ? trans('global.yes') : trans('global.no') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.countdown_enabled') }}
                        </th>
                        <td>
                            {{ $event->countdown_enabled ? trans('global.yes') : trans('global.no') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.registration_deadline') }}
                        </th>
                        <td>
                            {{ $event->registration_deadline ? $event->registration_deadline->format('Y-m-d H:i') : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $event->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $event->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.favicon_url') }}
                        </th>
                        <td>
                            {{ $event->favicon_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.og_image') }}
                        </th>
                        <td>
                            {{ $event->og_image }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.calendar_enabled') }}
                        </th>
                        <td>
                            {{ $event->calendar_enabled ? trans('global.yes') : trans('global.no') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.zalo_url') }}
                        </th>
                        <td>
                            {{ $event->zalo_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.fanpage_url') }}
                        </th>
                        <td>
                            {{ $event->fanpage_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.about_description') }}
                        </th>
                        <td>
                            {!! $event->about_description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.about_where') }}
                        </th>
                        <td>
                            {!! $event->about_where !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.about_when') }}
                        </th>
                        <td>
                            {!! $event->about_when !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.speakers') }}
                        </th>
                        <td>
                            {{ $event->speakers_count ?? $event->speakers->count() }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection
