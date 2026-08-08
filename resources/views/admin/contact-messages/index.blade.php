@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Contact Messages
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="mb-3">
            <div class="form-row align-items-end">
                <div class="form-group col-md-4 mb-0">
                    <label for="event_id">Event</label>
                    <select name="event_id" id="event_id" class="form-control">
                        <option value="">All sources</option>
                        <option value="0" {{ request('event_id') !== null && request('event_id') === '0' ? 'selected' : '' }}>
                            Home Page
                        </option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-0">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    @if(request('event_id') !== null && request('event_id') !== '')
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-default">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ContactMessage">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Event
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Subject
                        </th>
                        <th>
                            Received
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contactMessages as $key => $contactMessage)
                        <tr data-entry-id="{{ $contactMessage->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $contactMessage->id ?? '' }}
                            </td>
                            <td>
                                {{ $contactMessage->source_label }}
                            </td>
                            <td>
                                {{ $contactMessage->name ?? '' }}
                            </td>
                            <td>
                                {{ $contactMessage->email ?? '' }}
                            </td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($contactMessage->subject ?? '', 60) }}
                            </td>
                            <td>
                                {{ $contactMessage->created_at ? $contactMessage->created_at->format('d M Y H:i') : '' }}
                            </td>
                            <td>
                                @if($contactMessage->read_at)
                                    <span class="badge badge-pill" style="background: var(--green-light); color: var(--primary-active);">Read</span>
                                @else
                                    <span class="badge badge-pill" style="background: var(--primary); color: #fff;">New</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.contacts.show', $contactMessage->id) }}">
                                    {{ trans('global.view') }}
                                </a>

                                <form action="{{ route('admin.contacts.destroy', $contactMessage->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>

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

  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.contacts.massDestroy') }}",
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

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-ContactMessage:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection
