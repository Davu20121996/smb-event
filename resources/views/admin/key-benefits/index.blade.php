@extends('layouts.admin')
@section('content')
@can('key_benefit_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.key-benefits.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.keyBenefit.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.keyBenefit.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-KeyBenefit">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.icon') }}
                        </th>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.keyBenefit.fields.sort_order') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($keyBenefits as $key => $keyBenefit)
                        <tr data-entry-id="{{ $keyBenefit->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $keyBenefit->id ?? '' }}
                            </td>
                            <td>
                                @if($keyBenefit->icon)
                                    <i class="fa {{ $keyBenefit->icon }}"></i> {{ $keyBenefit->icon }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td>
                                {{ $keyBenefit->title ?? '' }}
                            </td>
                            <td>
                                {{ $keyBenefit->description ?? '' }}
                            </td>
                            <td>
                                {{ $keyBenefit->sort_order ?? '' }}
                            </td>
                            <td>
                                @can('key_benefit_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.key-benefits.show', $keyBenefit->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('key_benefit_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.key-benefits.edit', $keyBenefit->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('key_benefit_delete')
                                    <form action="{{ route('admin.key-benefits.destroy', $keyBenefit->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('key_benefit_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.key-benefits.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-KeyBenefit:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection
