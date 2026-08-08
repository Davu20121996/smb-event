@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Khách đăng ký Event
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.attendees.index') }}" class="mb-3">
            <div class="form-row align-items-end">
                <div class="form-group col-md-4 mb-0">
                    <label for="event_id">Sự kiện</label>
                    <select name="event_id" id="event_id" class="form-control">
                        <option value="">Tất cả sự kiện</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3 mb-0">
                    <label for="search">Tìm kiếm</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Tên, email, SĐT, công ty, MST...">
                </div>
                <div class="form-group col-md-3 mb-0">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.attendees.index') }}" class="btn btn-default">Xóa bộ lọc</a>
                </div>
                <div class="form-group col-md-2 mb-0">
                    <form action="{{ route('admin.attendees.export') }}" method="POST" style="display:inline-block;">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ request('event_id') }}">
                        <button type="submit" class="btn btn-success"><i class="fa fa-download"></i> Xuất Excel</button>
                    </form>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover datatable datatable-Attendee">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>ID</th>
                        <th>Sự kiện</th>
                        <th>Họ và tên</th>
                        <th>Email công ty</th>
                        <th>Tên công ty</th>
                        <th>SĐT</th>
                        <th>Quy mô</th>
                        <th>Sản phẩm quan tâm</th>
                        <th>Mã QR</th>
                        <th>Check-in</th>
                        <th>Voucher</th>
                        <th>Trạng thái</th>
                        <th>Đăng ký lúc</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendees as $key => $attendee)
                        <tr data-entry-id="{{ $attendee->id }}">
                            <td></td>
                            <td>{{ $attendee->id ?? '' }}</td>
                            <td>{{ $attendee->event->name ?? '' }}</td>
                            <td>{{ $attendee->name ?? '' }}</td>
                            <td>{{ $attendee->email ?? '' }}</td>
                            <td>{{ $attendee->company ?? '' }}</td>
                            <td>{{ $attendee->phone ?? '' }}</td>
                            <td>{{ $attendee->company_size_label ?? '' }}</td>
                            <td>{{ $attendee->interested_products ?? '' }}</td>
                            <td>
                                @if($attendee->qr)
                                    <a href="{{ route('admin.attendees.qr', $attendee->id) }}" target="_blank" class="btn btn-xs btn-info" title="Xem QR">
                                        <i class="fa fa-qrcode"></i> {{ $attendee->qr }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($attendee->checked_in_at)
                                    <span class="badge badge-success" title="Bởi: {{ $attendee->checkinByWhom ?? '' }}" data-toggle="tooltip">
                                        {{ $attendee->checked_in_at->format('d M H:i') }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Chưa check-in</span>
                                @endif
                            </td>
                            <td>
                                @if($attendee->voucher)
                                    <span class="badge badge-success" title="{{ $attendee->voucher->name }} ({{ $attendee->voucher->discount_label }})">
                                        {{ $attendee->voucher->code }}
                                    </span>
                                    <button type="button" class="btn btn-xs btn-secondary btn-active-voucher" data-name="{{ $attendee->name }}"
                                            data-company="{{ $attendee->company ?? '' }}" data-attendee="{{ $attendee->id }}" data-has-voucher="1" title="Đổi voucher">
                                        🔄 Đổi
                                    </button>
                                @else
                                    <button type="button" class="btn btn-xs btn-secondary btn-active-voucher" data-name="{{ $attendee->name }}"
                                            data-company="{{ $attendee->company ?? '' }}" data-attendee="{{ $attendee->id }}" data-has-voucher="0"
                                            title="Chưa có voucher — bấm để kích hoạt">
                                        🎫 Active Voucher
                                    </button>
                                @endif
                            </td>
                            <td>
                                <select class="attendee-status form-control form-control-sm" data-id="{{ $attendee->id }}">
                                    @foreach(\App\Attendee::STATUS_LABELS as $value => $label)
                                        <option value="{{ $value }}" {{ $attendee->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if($attendee->status === 'rsvp_confirmed')
                                    <span class="badge badge-pill mt-1" style="background:#16a34a; color:#fff; font-size:11px;">✅ Đã xác nhận tham dự</span>
                                @endif
                            </td>
                            <td>{{ $attendee->created_at ? $attendee->created_at->format('d M Y H:i') : '' }}</td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.attendees.show', $attendee->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                                <form action="{{ route('admin.attendees.sendVerify', $attendee->id) }}" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-secondary" value="Mail verify">
                                </form>
                                <form action="{{ route('admin.attendees.sendTicket', $attendee->id) }}" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-warning" value="Gửi vé">
                                </form>
                                @if($attendee->voucher)
                                    <form action="{{ route('admin.attendees.sendVoucherEmail', $attendee->id) }}" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-success" value="📧 Gửi email voucher">
                                    </form>
                                @endif
                                <form action="{{ route('admin.attendees.destroy', $attendee->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

@can('voucher_assign')
<div class="modal fade" id="activeVoucherModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✅ Active Voucher cho khách</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p><strong id="avModalAttendee"></strong></p>
                <p class="text-muted" id="avModalCompany"></p>

                <div class="form-group">
                    <label for="avVoucherSelect">Chọn Voucher:</label>
                    <select id="avVoucherSelect" class="form-control">
                        <option value="">-- Chọn voucher --</option>
                        @foreach($activeVouchers as $v)
                            <option value="{{ $v->id }}" data-label="{{ $v->discount_label }}" data-name="{{ $v->name }}"
                                    data-remaining="{{ $v->remaining_uses === null ? '∞' : $v->remaining_uses }}">
                                {{ $v->code }} — {{ $v->discount_label }} (còn {{ $v->remaining_uses === null ? '∞' : $v->remaining_uses }} lượt)
                            </option>
                        @endforeach
                    </select>
                    @if($activeVouchers->isEmpty())
                        <p class="text-danger mt-2">Chưa có voucher active nào. <a href="{{ route('admin.vouchers.create') }}">Tạo voucher</a> trước.</p>
                    @endif
                </div>

                <div class="alert alert-info" id="avPreview" style="display:none;">
                    <strong>Xem trước ưu đãi:</strong><br>
                    <span id="avPreviewText"></span>
                </div>

                <div class="form-group">
                    <label for="avNote">Ghi chú nội bộ:</label>
                    <input type="text" id="avNote" class="form-control" placeholder="VD: Tặng thêm cho khách VIP">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="avSendEmail" checked>
                    <label class="form-check-label" for="avSendEmail">Gửi email thông báo voucher cho khách</label>
                </div>
                <div class="alert alert-danger" id="avError" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="avConfirm">✅ Kích Hoạt</button>
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

        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.attendees.massDestroy') }}",
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
                        headers: { 'x-csrf-token': _token },
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    }).done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)

        $.extend(true, $.fn.dataTable.defaults, {
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        });
        $('.datatable-Attendee:not(.ajaxTable)').DataTable({ buttons: dtButtons })

        $(document).on('change', '.attendee-status', function () {
            var $sel = $(this);
            $.ajax({
                headers: { 'x-csrf-token': _token },
                method: 'POST',
                url: '{{ route("admin.attendees.updateStatus") }}',
                data: { id: $sel.data('id'), status: $sel.val() },
                success: function () {},
                error: function () { alert('Cập nhật trạng thái thất bại'); }
            });
        });
    });

    @can('voucher_assign')
    $(function () {
        let avAttendeeId = null;
        let avForce = false;

        $(document).on('click', '.btn-active-voucher', function () {
            avAttendeeId = $(this).data('attendee');
            avForce = false;
            $('#avModalAttendee').text($(this).data('name'));
            $('#avModalCompany').text($(this).data('company') || '');
            $('#avNote').val('');
            $('#avError').hide();
            $('#avPreview').hide();
            $('#avVoucherSelect').val('');
            $('#activeVoucherModal').modal('show');
        });

        $('#avVoucherSelect').on('change', function () {
            var opt = $(this).find(':selected');
            if (opt.val()) {
                $('#avPreviewText').text(opt.data('name') + ' — ' + opt.data('label') + ' (còn ' + opt.data('remaining') + ' lượt)');
                $('#avPreview').show();
            } else {
                $('#avPreview').hide();
            }
        });

        $('#avConfirm').on('click', function () {
            var voucherId = $('#avVoucherSelect').val();
            if (!voucherId) { alert('Vui lòng chọn voucher.'); return; }
            $('#avError').hide();
            $.ajax({
                headers: { 'x-csrf-token': _token },
                method: 'POST',
                url: '{{ route('admin.attendees.activateVoucher', '__ATTENDEE__') }}'.replace('__ATTENDEE__', avAttendeeId),
                data: {
                    voucher_id: voucherId,
                    send_email: $('#avSendEmail').is(':checked') ? 1 : 0,
                    note: $('#avNote').val(),
                    force: avForce ? 1 : 0
                },
                success: function (res) {
                    if (res.status === 'success') {
                        $('#activeVoucherModal').modal('hide');
                        alert(res.message);
                        location.reload();
                    }
                },
                error: function (xhr) {
                    var res = xhr.responseJSON;
                    if (res && res.code === 'ALREADY_HAS_VOUCHER') {
                        avForce = true;
                        if (confirm(res.message + ' Chọn OK để thay thế voucher cũ.')) {
                            $('#avConfirm').trigger('click');
                        } else {
                            avForce = false;
                        }
                        return;
                    }
                    if (res && res.message) {
                        $('#avError').text(res.message).show();
                    } else {
                        $('#avError').text('Kích hoạt voucher thất bại.').show();
                    }
                }
            });
        });
    });
    @endcan
</script>
@endsection