@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.event.title_singular') }}
    </div>

    <div class="card-body">
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i>
            <strong>Lưu ý:</strong> Sau khi tạo sự kiện, bạn sẽ được chuyển sang trang quản lý chi tiết để thêm Diễn giả, Lịch trình, Địa điểm, Nhà tài trợ và các thông tin khác.
        </div>
        <form action="{{ route("admin.events.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.partials.multilang_hint', ['mlFields' => ['name', 'description', 'about_description', 'about_where', 'about_when'], 'isCreate' => true])
            @include('admin.events._form_fields')
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
  $('.datepicker').datetimepicker({
    format: 'YYYY-MM-DD',
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down',
      previous: 'fa fa-chevron-left',
      next: 'fa fa-chevron-right'
    }
  });
  $('.datetimepicker').datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down',
      previous: 'fa fa-chevron-left',
      next: 'fa fa-chevron-right'
    }
  });
});

</script>
@endsection
