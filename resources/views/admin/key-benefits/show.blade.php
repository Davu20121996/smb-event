@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.keyBenefit.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.id') }}
                        </th>
                        <td>
                            {{ $keyBenefit->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.icon') }}
                        </th>
                        <td>
                            @if($keyBenefit->icon)
                                <i class="fa {{ $keyBenefit->icon }}"></i> {{ $keyBenefit->icon }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.title') }}
                        </th>
                        <td>
                            {{ $keyBenefit->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.description') }}
                        </th>
                        <td>
                            {!! $keyBenefit->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.sort_order') }}
                        </th>
                        <td>
                            {{ $keyBenefit->sort_order }}
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
