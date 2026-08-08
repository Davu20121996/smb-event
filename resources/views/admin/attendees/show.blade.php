@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Khách đăng ký #{{ $attendee->id }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th style="width: 200px;">ID</th>
                        <td>{{ $attendee->id }}</td>
                    </tr>
                    <tr>
                        <th>Sự kiện</th>
                        <td>{{ $attendee->event->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Họ và tên</th>
                        <td>{{ $attendee->name }}</td>
                    </tr>
                    <tr>
                        <th>Email công ty</th>
                        <td><a href="mailto:{{ $attendee->email }}">{{ $attendee->email }}</a></td>
                    </tr>
                    <tr>
                        <th>Tên công ty</th>
                        <td>{{ $attendee->company ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Mã số thuế (MST)</th>
                        <td>{{ $attendee->tax_code ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $attendee->phone ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Quy mô doanh nghiệp</th>
                        <td>{{ $attendee->company_size_label ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Sản phẩm quan tâm</th>
                        <td>{{ $attendee->interested_products ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Loại vé</th>
                        <td>{{ $attendee->ticket_type ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <span class="badge badge-pill"
                                  style="background:
                                    {{ $attendee->status === 'confirmed' ? 'var(--green-light)' : ($attendee->status === 'cancelled' ? '#f8d7da' : 'var(--primary)') }}; color:
                                    {{ $attendee->status === 'confirmed' ? 'var(--primary-active)' : ($attendee->status === 'cancelled' ? '#842029' : '#fff') }};">
                                {{ $attendee->status_label }}
                            </span>
                        </td>
                    </tr>
                    @if($attendee->notes)
                        <tr>
                            <th>Ghi chú</th>
                            <td>{{ $attendee->notes }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Đăng ký lúc</th>
                        <td>{{ $attendee->created_at ? $attendee->created_at->format('d M Y H:i') : '' }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-3 row">
                <div class="col-md-5">
                    <div class="card text-center p-3">
                        <h4 class="mb-2">Mã QR Check-in</h4>
                        @if($attendee->qr)
                            <div>
                                <img src="{{ route('admin.attendees.qr', $attendee->id) }}" alt="QR" style="width:220px; height:220px; background:#fff;">
                            </div>
                            <p class="mt-2 mb-0 font-weight-bold">{{ $attendee->qr }}</p>
                        @else
                            <p class="text-muted">Chưa có mã QR.</p>
                        @endif
                        <form action="{{ route('admin.attendees.generateQr', $attendee->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">
                                <i class="fa fa-qrcode"></i> Tạo / Tạo lại mã QR
                            </button>
                        </form>
                        <form action="{{ route('admin.attendees.sendTicket', $attendee->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fa fa-envelope"></i> Gửi vé qua email
                            </button>
                        </form>
                        <form action="{{ route('admin.attendees.sendVerify', $attendee->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">
                                <i class="fa fa-check"></i> Gửi mail verify
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>

@can('voucher_assign')
<div class="card mt-3">
    <div class="card-header">
        VOUCHER ƯU ĐÃI
    </div>
    <div class="card-body">
        @if($attendee->voucher)
            <span class="badge badge-success mb-2" style="font-size:13px;">🟢 Đã kích hoạt voucher</span>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr><th style="width:200px;">Mã</th><td><strong>{{ $attendee->voucher->code }}</strong></td></tr>
                    <tr><th>Tên</th><td>{{ $attendee->voucher->name }}</td></tr>
                    <tr><th>Ưu đãi</th><td>{{ $attendee->voucher->discount_label }}{{ $attendee->discount_amount > 0 ? ' — ' . number_format($attendee->discount_amount, 0, ',', '.') . 'đ' : '' }}</td></tr>
                    <tr><th>Kích hoạt lúc</th><td>{{ $attendee->voucherAssignment?->assigned_at ? $attendee->voucherAssignment->assigned_at->format('d/m/Y H:i') : 'N/A' }}</td></tr>
                    <tr><th>Bởi</th><td>{{ $attendee->voucherAssignment?->assigner?->name ?? '—' }}</td></tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-warning btn-active-voucher" data-name="{{ $attendee->name }}" data-company="{{ $attendee->company ?? '' }}" data-attendee="{{ $attendee->id }}"><i class="fa fa-refresh"></i> Đổi Voucher</button>
            <button type="button" class="btn btn-sm btn-danger btn-revoke-voucher" data-attendee="{{ $attendee->id }}">❌ Thu hồi Voucher</button>
        @else
            <p class="text-muted">Trạng thái: <span class="badge badge-secondary">⬜ Chưa kích hoạt voucher</span></p>
            <button type="button" class="btn btn-success btn-open-active-voucher" data-name="{{ $attendee->name }}" data-company="{{ $attendee->company ?? '' }}" data-attendee="{{ $attendee->id }}">
                ✅ Active Voucher cho khách này
            </button>
        @endif
    </div>
</div>

@include('admin.attendees._voucher_modal')
@endcan

@endsection
@section('scripts')
@parent
@include('admin.attendees._voucher_scripts')
@endsection