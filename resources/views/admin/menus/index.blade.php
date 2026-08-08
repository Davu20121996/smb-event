@extends('layouts.admin')
@section('content')
@can('menu_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.menus.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.menu.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.menu.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Menu">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.label') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.parent') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.url') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.sort_order') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.is_active') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $key => $menu)
                        <tr data-entry-id="{{ $menu->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $menu->id ?? '' }}
                            </td>
                            <td>
                                {{ $menu->label ?? '' }}
                            </td>
                            <td>
                                {{ trans('global.none') }}
                            </td>
                            <td>
                                {{ $menu->url ?? '' }}
                            </td>
                            <td>
                                {{ $menu->sort_order ?? '' }}
                            </td>
                            <td>
                                {{ $menu->is_active ? trans('global.yes') : trans('global.no') }}
                            </td>
                            <td>
                                @can('menu_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.menus.show', $menu->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('menu_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.menus.edit', $menu->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('menu_delete')
                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                        @if($menu->children->count())
                            @foreach($menu->children as $child)
                                <tr data-entry-id="{{ $child->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $child->id ?? '' }}
                                    </td>
                                    <td>
                                        &mdash; {{ $child->label ?? '' }}
                                    </td>
                                    <td>
                                        {{ $menu->label ?? '' }}
                                    </td>
                                    <td>
                                        {{ $child->url ?? '' }}
                                    </td>
                                    <td>
                                        {{ $child->sort_order ?? '' }}
                                    </td>
                                    <td>
                                        {{ $child->is_active ? trans('global.yes') : trans('global.no') }}
                                    </td>
                                    <td>
                                        @can('menu_show')
                                            <a class="btn btn-xs btn-primary" href="{{ route('admin.menus.show', $child->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan

                                        @can('menu_edit')
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.menus.edit', $child->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('menu_delete')
                                            <form action="{{ route('admin.menus.destroy', $child->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                            </form>
                                        @endcan

                                    </td>

                                </tr>
                            @endforeach
                        @endif
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
@can('menu_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.menus.massDestroy') }}",
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
    order: [[ 1, 'asc' ]],
    pageLength: 100,
  });
  $('.datatable-Menu:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection
