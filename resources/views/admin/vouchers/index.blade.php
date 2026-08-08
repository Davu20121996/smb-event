@extends('layouts.admin')
@section('content')
@can('voucher_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.vouchers.create') }}">
                <i class="fa fa-plus"></i> Tạo Voucher mới
            </a>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#quickCreateModal">
                <i class="fa fa-bolt"></i> Tạo nhanh Voucher
            </button>
            @can('voucher_assign')
                @if(!$activeVouchers->isEmpty())
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#quickAssignModal">
                        <i class="fa fa-tag"></i> Gán nhanh Voucher
                    </button>
                @endif
            @endcan
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        Quản lý Voucher
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.vouchers.index') }}" class="mb-3">
            <div class="form-row align-items-end">
                <div class="form-group col-md-3 mb-0">
                    <label for="search">Tìm mã / tên</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Mã / tên voucher...">
                </div>
                <div class="form-group col-md-3 mb-0">
                    <label for="event_id">Sự kiện</label>
                    <select name="event_id" id="event_id" class="form-control">
                        <option value="">Tất cả sự kiện</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-0">
                    <label for="type">Loại</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">Tất cả loại</option>
                        @foreach(\App\Voucher::TYPES as $type => $label)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-0">
                    <label for="status">Trạng thái</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Tất cả</option>
                        @foreach(\App\Voucher::STATUS_LABELS as $st => $label)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-0">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Lọc</button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-default">Bỏ lọc</a>
                </div>
            </div>
        </form>

        @if (session('success'))
            <div class="row mb-2">
                <div class="col-lg-12">
                    <div class="alert alert-success">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Voucher">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Mã</th>
                        <th>Tên Voucher</th>
                        <th>Loại</th>
                        <th>Đã dùng</th>
                        <th>Hạn dùng</th>
                        <th>Trạng thái</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vouchers as $key => $voucher)
                        <tr data-entry-id="{{ $voucher->id }}">
                            <td></td>
                            <td><strong>{{ $voucher->code }}</strong></td>
                            <td>{{ $voucher->name }}</td>
                            <td>{{ $voucher->type_label }}</td>
                            <td style="min-width: 140px;">
                                @php
                                    $pct = $voucher->max_uses ? min(100, round($voucher->used_count / $voucher->max_uses * 100)) : 0;
                                    $barColor = $pct < 70 ? 'var(--green-light)' : ($pct <= 90 ? '#ffc107' : '#dc3545');
                                @endphp
                                @if($voucher->max_uses)
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
                                    </div>
                                    <small>{{ $voucher->used_count }} / {{ $voucher->max_uses }} ({{ $pct }}%)</small>
                                @else
                                    <small>{{ $voucher->used_count }} / ∞</small>
                                @endif
                            </td>
                            <td>{{ $voucher->valid_until ? $voucher->valid_until->format('d/m/Y') . ' ' . $voucher->valid_until->format('H:i') : 'Vô hạn' }}</td>
                            <td>
                                @if($voucher->status === 'active')
                                    <span class="badge badge-success">🟢 Đang hoạt động</span>
                                @elseif($voucher->status === 'inactive')
                                    <span class="badge badge-danger">🔴 Vô hiệu hóa</span>
                                @else
                                    <span class="badge badge-secondary">⚫ Hết hạn</span>
                                @endif
                            </td>
                            <td>
                                @can('voucher_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.vouchers.show', $voucher->id) }}">
                                        <i class="fa fa-eye"></i> {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('voucher_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.vouchers.edit', $voucher->id) }}">
                                        <i class="fa fa-pencil-alt"></i> {{ trans('global.edit') }}
                                    </a>
                                    @if($voucher->status === 'active')
                                        <form action="{{ route('admin.vouchers.deactivate', $voucher->id) }}" method="POST" onsubmit="return confirm('Vô hiệu hóa voucher này?');" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-warning"><i class="fa fa-ban"></i> Vô hiệu hóa</button>
                                        </form>
                                    @endif
                                @endcan
                                @can('voucher_delete')
                                    @if($voucher->used_count === 0)
                                        <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">{{ trans('global.delete') }}</button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="quickCreateModal" tabindex="-1" role="dialog" aria-labelledby="quickCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickCreateModalLabel"><i class="fa fa-bolt"></i> Tạo nhanh Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <form id="quickCreateForm">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="qcCode">Mã voucher *</label>
                            <input type="text" id="qcCode" name="code" class="form-control" required maxlength="50" placeholder="VD: code do đơn vị cung cấp">
                            <div class="validation text-danger"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="qcName">Tên voucher *</label>
                            <input type="text" id="qcName" name="name" class="form-control" required maxlength="255">
                            <div class="validation text-danger"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="qcEvent">Sự kiện áp dụng</label>
                            <select name="event_id" id="qcEvent" class="form-control">
                                <option value="">Toàn hệ thống</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="qcType">Loại ưu đãi *</label>
                            <select name="type" id="qcType" class="form-control">
                                @foreach(\App\Voucher::TYPES as $type => $label)
                                    <option value="{{ $type }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6" id="qcValueField">
                            <label for="qcValue">Giá trị</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" id="qcValue" name="value" class="form-control" value="0">
                                <div class="input-group-append"><span class="input-group-text" id="qcValueUnit">%</span></div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="qcMaxUses">Số lượt dùng tối đa</label>
                            <input type="number" min="1" id="qcMaxUses" name="max_uses" class="form-control" placeholder="Để trống = không giới hạn">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="qcValidFrom">Hiệu lực từ</label>
                            <input type="text" id="qcValidFrom" name="valid_from" class="form-control datetime">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="qcValidUntil">Hiệu lực đến</label>
                            <input type="text" id="qcValidUntil" name="valid_until" class="form-control datetime">
                        </div>
                    </div>
                    <div class="form-check mx-2">
                        <input type="checkbox" class="form-check-input" name="is_single_use" id="qcSingleUse" value="1">
                        <label class="form-check-label" for="qcSingleUse">Mỗi người chỉ dùng 1 lần</label>
                    </div>
                    <input type="hidden" name="is_assignable" value="1">
                    <input type="hidden" name="status" value="active">
                    <div class="alert alert-danger" id="qcError" style="display:none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="btnQuickCreate">
                        <i class="fa fa-check"></i> Tạo nhanh Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@can('voucher_assign')
<div class="modal fade" id="quickAssignModal" tabindex="-1" role="dialog" aria-labelledby="quickAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickAssignModalLabel"><i class="fa fa-tag"></i> Gán nhanh Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                @if($activeVouchers->isEmpty())
                    <div class="alert alert-warning">Chưa có voucher đang hoạt động nào để gán.</div>
                @else
                    <div class="form-group">
                        <label for="qaVoucher">Chọn Voucher</label>
                        <select id="qaVoucher" class="form-control">
                            @foreach($activeVouchers as $v)
                                <option value="{{ $v->id }}" data-remaining="{{ $v->remaining_uses === null ? '∞' : $v->remaining_uses }}"
                                        data-display="{{ $v->name }} ({{ $v->discount_label }})">
                                    {{ $v->code }} — {{ $v->name }} ({{ $v->discount_label }}) — còn {{ $v->remaining_uses === null ? '∞' : $v->remaining_uses }} lượt
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qaSearch">Tìm khách</label>
                        <input type="text" id="qaSearch" class="form-control" placeholder="Tên / email / công ty...">
                    </div>
                    <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                        <table class="table table-bordered table-striped table-hover" id="qaTable">
                            <thead>
                                <tr>
                                    <th width="40"><input type="checkbox" id="qaAll"></th>
                                    <th>Họ và tên</th>
                                    <th>Email</th>
                                    <th>Công ty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendees as $a)
                                    <tr data-name="{{ strtolower($a->name) }}" data-email="{{ strtolower($a->email) }}" data-company="{{ strtolower($a->company ?? '') }}">
                                        <td><input type="checkbox" class="qa-check" value="{{ $a->id }}"></td>
                                        <td>{{ $a->name }}</td>
                                        <td>{{ $a->email }}</td>
                                        <td>{{ $a->company ?? '' }}</td>
                                    </tr>
                                @endforeach
                                @if($attendees->isEmpty())
                                    <tr><td colspan="4" class="text-center text-muted">Không có khách nào chưa có voucher.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="qaSendEmail" checked>
                        <label class="form-check-label" for="qaSendEmail">Gửi email thông báo cho khách</label>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnQuickAssign">
                    <i class="fa fa-tag"></i> Gán Voucher
                </button>
            </div>
        </div>
    </div>
</div>
@endcan

@endsection
@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    @can('voucher_delete')
        let deleteButton = {
            text: '{{ trans('global.datatables.delete') }}',
            url: "{{ route('admin.vouchers.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });
                if (ids.length === 0) { alert('{{ trans('global.datatables.zero_selected') }}'); return }
                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({ headers: { 'x-csrf-token': _token }, method: 'POST', url: config.url, data: { ids: ids } })
                        .done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
    @endcan
        $.extend(true, $.fn.dataTable.defaults, { order: [[ 1, 'asc' ]], pageLength: 100 });
        $('.datatable-Voucher:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    })

    @can('voucher_assign')
    $('#quickAssignModal').on('show.bs.modal', function () {
        $('#qaVoucher').val($('#qaVoucher option:first').val());
        $('.qa-check').prop('checked', false);
        $('#qaAll').prop('checked', false);
        $('#qaSearch').val('').trigger('input');
    });
    $('#qaSearch').on('input', function () {
        var q = $(this).val().toLowerCase();
        $('#qaTable tbody tr').each(function () {
            $(this).toggle(
                !q ||
                $(this).data('name').indexOf(q) !== -1 ||
                $(this).data('email').indexOf(q) !== -1 ||
                $(this).data('company').indexOf(q) !== -1
            );
        });
    });
    $('#qaAll').on('change', function () {
        $('#qaTable tbody tr:visible .qa-check').prop('checked', this.checked);
    });
    $(document).on('change', '.qa-check', function () {
        var total = $('#qaTable tbody input.qa-check:visible').length;
        var checked = $('#qaTable tbody input.qa-check:visible:checked').length;
        $('#qaAll').prop('checked', total > 0 && total === checked);
    });
    $('#btnQuickAssign').on('click', function () {
        var voucherId = $('#qaVoucher').val();
        var ids = [];
        $('#qaTable tbody input.qa-check:checked').each(function () { ids.push($(this).val()); });
        if (!voucherId || ids.length === 0) { alert('Vui lòng chọn voucher và ít nhất 1 khách.'); return; }
        var btn = $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang gán...');
        $.ajax({
            headers: { 'x-csrf-token': _token },
            method: 'POST',
            url: '{{ route("admin.vouchers.assign", "__ID__") }}'.replace('__ID__', voucherId),
            data: { attendee_ids: ids, send_email: $('#qaSendEmail').is(':checked') ? 1 : 0 },
            success: function (res) {
                alert(res.message || 'Gán voucher thành công.');
                $('#quickAssignModal').modal('hide');
                location.reload();
            },
            error: function (xhr) {
                var res = xhr.responseJSON || {};
                alert(res.message || 'Có lỗi xảy ra.');
                btn = $(btn).attr('disabled', false).html('<i class="fa fa-tag"></i> Gán Voucher');
            }
        });
    });
    @endcan

    @can('voucher_create')
    $(function () {
        function qcValueConfig() {
            var type = $('#qcType').val();
            var visible = (type === 'discount_percent' || type === 'discount_fixed');
            $('#qcValueField').toggle(visible);
            if (!visible) { $('#qcValue').val(0); }
            $('#qcValueUnit').text(type === 'discount_percent' ? '%' : 'đ');
        }
        $('#qcType').on('change', qcValueConfig);

        $('#quickCreateModal').on('show.bs.modal', function () {
            $('#quickCreateForm')[0].reset();
            $('#qcError').hide().empty();
            $('.validation').hide();
            if (window.$ && $.fn.datetimepicker) {
                if ($.fn.datetimepicker && $.fn.datetimepicker.defaults && !$(this).find('.datetime').data('DateTimePicker')) {
                    $(this).find('.datetime').datetimepicker({ format: 'Y-m-d H:i:s', defaultTime: false });
                }
            }
            qcValueConfig();
        });

        $('#quickCreateForm').on('submit', function (e) {
            e.preventDefault();
            $('#qcError').hide();
            var btn = $('#btnQuickCreate').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang tạo...');
            $.ajax({
                headers: { 'x-csrf-token': _token },
                method: 'POST',
                url: '{{ route("admin.vouchers.store") }}',
                data: $(this).serialize(),
                success: function (res) {
                    $('#quickCreateModal').modal('hide');
                    location.reload();
                },
                error: function (xhr) {
                    var res = xhr.responseJSON || {};
                    var msg = [];
                    if (res && res.errors) {
                        $.each(res.errors, function (k, v) { msg.push('<strong>' + k + ':</strong> ' + v[0]); });
                    } else {
                        msg.push(res.message || 'Có lỗi xảy ra.');
                    }
                    $('#qcError').html(msg.join('<br>')).show();
                    btn = $('#btnQuickCreate').attr('disabled', false).html('<i class="fa fa-check"></i> Tạo nhanh Voucher');
                }
            });
        });
    });
    @endcan
</script>
@endsection