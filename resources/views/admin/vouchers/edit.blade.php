@extends('layouts.admin')
@section('content')

@if (session('success'))
    <div class="row mb-2">
        <div class="col-lg-12">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    </div>
@endif
@if (session('error'))
    <div class="row mb-2">
        <div class="col-lg-12">
            <div class="alert alert-danger">{{ session('error') }}</div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        Chỉnh sửa Voucher: <strong>{{ $voucher->code }}</strong>
        <a class="btn btn-sm btn-default float-right" href="{{ route('admin.vouchers.index') }}"><i class="fa fa-arrow-left"></i> Danh sách</a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.vouchers._form')
            <div>
                <input class="btn btn-danger" type="submit" value="Lưu Voucher">
            </div>
        </form>
    </div>
</div>

@can('voucher_assign')
<div class="card mt-3">
    <div class="card-header">
        Gán Voucher <strong>{{ $voucher->code }}</strong> cho khách đã đăng ký
        <span class="badge badge-info">Đã dùng: {{ $voucher->used_count }} / {{ $voucher->max_uses ?? '∞' }}</span>
        @if(!$voucher->is_assignable)
            <span class="badge badge-warning">Voucher không cho phép gán riêng</span>
        @endif
    </div>

    <div class="card-body">
        <div class="form-row align-items-end mb-3">
            <div class="form-group col-md-3 mb-0">
                <label for="assignSearch">Tìm kiếm</label>
                <input type="text" id="assignSearch" class="form-control" placeholder="Tên / email / công ty...">
            </div>
            <div class="form-group col-md-3 mb-0">
                <button type="button" class="btn btn-xs btn-info select-all-assign">Chọn tất cả</button>
                <button type="button" class="btn btn-xs btn-info deselect-all-assign">Bỏ chọn</button>
            </div>
            <div class="form-group col-md-3 mb-0">
                <div class="form-check mt-4">
                    <input type="checkbox" class="form-check-input" id="assignSendEmail" checked>
                    <label class="form-check-label" for="assignSendEmail">Gửi email thông báo cho khách</label>
                </div>
            </div>
            <div class="form-group col-md-3 mb-0 text-right">
                <button type="button" class="btn btn-primary" id="btnAssignVouchers" @if(!$voucher->is_assignable || $voucher->status !== 'active') disabled @endif>
                    <i class="fa fa-tag"></i> Gán Voucher
                </button>
            </div>
        </div>

        <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-hover" id="assignTable">
                <thead>
                    <tr>
                        <th width="40"><input type="checkbox" id="assignAll"></th>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Công ty</th>
                        <th>Trạng thái</th>
                        <th>Voucher hiện tại</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendees as $attendee)
                        <tr data-name="{{ strtolower($attendee->name) }}" data-email="{{ strtolower($attendee->email) }}" data-company="{{ strtolower($attendee->company ?? '') }}">
                            <td><input type="checkbox" class="assign-check" value="{{ $attendee->id }}" @if($assignedIds->contains($attendee->id)) disabled @endif></td>
                            <td>{{ $attendee->id }}</td>
                            <td>{{ $attendee->name }}</td>
                            <td>{{ $attendee->email }}</td>
                            <td>{{ $attendee->company ?? '' }}</td>
                            <td>{{ $attendee->status_label }}</td>
                            <td>
                                @if($assignedIds->contains($attendee->id))
                                    <span class="badge badge-success">Đã có voucher</span>
                                @elseif($attendee->voucher_id)
                                    <span class="badge badge-warning">Voucher khác</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="helper-block mt-2">Voucher còn <strong>{{ $voucher->remaining_uses ?? '∞' }}</strong> lượt có thể gán.</p>
    </div>
</div>
@endcan

@endsection
@section('scripts')
@parent
<script>
    $(function () {
        @can('voucher_assign')
        $('#assignAll').on('change', function () {
            $('.assign-check:visible:not(:disabled)').prop('checked', this.checked);
        });
        $('.select-all-assign').on('click', function () {
            $('.assign-check:visible:not(:disabled)').prop('checked', true);
        });
        $('.deselect-all-assign').on('click', function () {
            $('.assign-check:visible').prop('checked', false);
        });
        $('#assignSearch').on('input', function () {
            var q = $(this).val().toLowerCase();
            $('#assignTable tbody tr').each(function () {
                var row = $(this);
                var match = row.data('name').indexOf(q) >= 0 || row.data('email').indexOf(q) >= 0 || row.data('company').indexOf(q) >= 0;
                row.toggle(match);
            });
        });
        $('#btnAssignVouchers').on('click', function () {
            var ids = [];
            $('.assign-check:checked').each(function () { ids.push($(this).val()); });
            if (ids.length === 0) { alert('Vui lòng chọn ít nhất 1 khách.'); return; }
            if (!confirm('Gán voucher ' + '{{ $voucher->code }}' + ' cho ' + ids.length + ' khách?')) return;
            $.ajax({
                headers: { 'x-csrf-token': _token },
                method: 'POST',
                url: '{{ route('admin.vouchers.assign', $voucher->id) }}',
                data: { attendee_ids: ids, send_email: $('#assignSendEmail').is(':checked') ? 1 : 0 },
                success: function (res) {
                    alert(res.message);
                    if (res.status === 'success') location.reload();
                },
                error: function (xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gán voucher thất bại.';
                    alert(msg);
                }
            });
        });
        @endcan
    });
</script>
@endsection