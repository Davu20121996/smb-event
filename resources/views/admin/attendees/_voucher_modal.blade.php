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
                        @foreach($activeVouchers ?? [] as $v)
                            <option value="{{ $v->id }}" data-label="{{ $v->discount_label }}" data-name="{{ $v->name }}"
                                    data-remaining="{{ $v->remaining_uses === null ? '∞' : $v->remaining_uses }}">
                                {{ $v->code }} — {{ $v->discount_label }} (còn {{ $v->remaining_uses === null ? '∞' : $v->remaining_uses }} lượt)
                            </option>
                        @endforeach
                    </select>
                    @if(($activeVouchers ?? collect())->isEmpty())
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