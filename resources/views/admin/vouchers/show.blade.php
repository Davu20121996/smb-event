@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Chi tiết Voucher: <strong>{{ $voucher->code }}</strong>
        <a class="btn btn-sm btn-info float-right mr-1" href="{{ route('admin.vouchers.edit', $voucher->id) }}"><i class="fa fa-pencil-alt"></i> Chỉnh sửa</a>
        <a class="btn btn-sm btn-default float-right mr-1" href="{{ route('admin.vouchers.index') }}"><i class="fa fa-arrow-left"></i> Danh sách</a>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr><th style="width:180px;">Mã</th><td><strong>{{ $voucher->code }}</strong></td></tr>
                        <tr><th>Tên</th><td>{{ $voucher->name }}</td></tr>
                        <tr><th>Loại</th><td>{{ $voucher->type_label }} — <span class="text-primary">{{ $voucher->discount_label }}</span></td></tr>
                        <tr><th>Sự kiện</th><td>{{ $voucher->event->name ?? 'Toàn hệ thống' }}</td></tr>
                        <tr><th>Hiệu lực</th><td>{{ $voucher->valid_from ? $voucher->valid_from->format('d/m/Y H:i') : 'Ngay lập tức' }} → {{ $voucher->valid_until ? $voucher->valid_until->format('d/m/Y H:i') : 'Vô hạn' }}</td></tr>
                        <tr><th>Trạng thái</th><td>{{ $voucher->status_label }}</td></tr>
                        <tr><th>Sử dụng 1 lần/người</th><td>{{ $voucher->is_single_use ? 'Có' : 'Không' }}</td></tr>
                        <tr><th>Cho phép gán riêng</th><td>{{ $voucher->is_assignable ? 'Có' : 'Không' }}</td></tr>
                        <tr><th>Tạo bởi</th><td>{{ $voucher->creator?->name ?? '—' }}</td></tr>
                        @if($voucher->description)
                            <tr><th>Mô tả</th><td>{{ $voucher->description }}</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Số lượt sử dụng</h5>
                    @php
                        $pct = $voucher->max_uses ? min(100, round($voucher->used_count / $voucher->max_uses * 100)) : 0;
                        $barColor = $pct < 70 ? 'var(--green-light)' : ($pct <= 90 ? '#ffc107' : '#dc3545');
                    @endphp
                    <div class="progress" style="height: 18px;">
                        <div class="progress-bar" style="width: {{ $pct }}%; background: {{ $barColor }};">{{ $pct }}%</div>
                    </div>
                    <p class="mt-2">{{ $voucher->used_count }} / {{ $voucher->max_uses ?? '∞' }} lượt</p>
                    <p class="text-muted">Còn lại: {{ $voucher->remaining_uses ?? '∞' }} lượt</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        Danh sách khách đã được gán / dùng voucher
    </div>
    <div class="card-body">
        @if($attendees->isEmpty())
            <p class="text-muted">Chưa có khách nào được gán voucher này.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Công ty</th>
                            <th>Gán lúc</th>
                            <th>Dùng lúc</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendees as $attendee)
                            <tr>
                                <td>{{ $attendee->name }}</td>
                                <td>{{ $attendee->email }}</td>
                                <td>{{ $attendee->company ?? '' }}</td>
                                <td>{{ $attendee->pivot->assigned_at ? $attendee->pivot->assigned_at->format('d/m/Y H:i') : '—' }}</td>
                                <td>{{ $attendee->pivot->used_at ? $attendee->pivot->used_at->format('d/m/Y H:i') : '—' }}</td>
                                <td>{{ \App\VoucherAttendee::STATUS_LABELS[$attendee->pivot->status] ?? $attendee->pivot->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection