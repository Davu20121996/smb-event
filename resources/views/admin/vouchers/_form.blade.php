@php
    $v = $voucher ?? null;
@endphp

<div class="form-row">
    <div class="form-group col-md-6 {{ $errors->has('event_id') ? 'has-error' : '' }}">
        <label for="event_id">Sự kiện áp dụng</label>
        <select name="event_id" id="event_id" class="form-control">
            <option value="">Toàn hệ thống</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}" {{ old('event_id', $v->event_id ?? '') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
            @endforeach
        </select>
        <p class="helper-block">Để trống = voucher áp dụng cho mọi sự kiện.</p>
    </div>
    <div class="form-group col-md-6 {{ $errors->has('name') ? 'has-error' : '' }}">
        <label for="name">Tên voucher *</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $v->name ?? '') }}" required maxlength="255">
        @if($errors->has('name'))
            <p class="help-block">{{ $errors->first('name') }}</p>
        @endif
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6 {{ $errors->has('code') ? 'has-error' : '' }}">
        <label for="code">Mã voucher *</label>
        <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $v->code ?? '') }}" required maxlength="50" placeholder="VD: code do đơn vị cung cấp">
        @if($errors->has('code'))
            <p class="help-block">{{ $errors->first('code') }}</p>
        @endif
        @if(isset($v) && $v->used_count > 0)
            <p class="helper-block text-warning">Voucher đã được sử dụng — không thể đổi mã.</p>
        @endif
    </div>
    <div class="form-group col-md-6 {{ $errors->has('type') ? 'has-error' : '' }}">
        <label for="type">Loại ưu đãi *</label>
        <select name="type" id="type" class="form-control">
            @foreach(\App\Voucher::TYPES as $type => $label)
                <option value="{{ $type }}" {{ old('type', $v->type ?? '') == $type ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @if($errors->has('type'))
            <p class="help-block">{{ $errors->first('type') }}</p>
        @endif
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6 {{ $errors->has('value') ? 'has-error' : '' }}" id="valueField">
        <label for="value">Giá trị *</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" id="value" name="value" class="form-control" value="{{ old('value', $v->value ?? 0) }}">
            <div class="input-group-append">
                <span class="input-group-text" id="valueUnit">%</span>
            </div>
        </div>
        <p class="helper-block" id="valueHelper">VD: 20 = giảm 20% giá vé.</p>
        @if($errors->has('value'))
            <p class="help-block">{{ $errors->first('value') }}</p>
        @endif
    </div>
    <div class="form-group col-md-6 {{ $errors->has('max_uses') ? 'has-error' : '' }}">
        <label for="max_uses">Số lượt dùng tối đa</label>
        <input type="number" min="1" id="max_uses" name="max_uses" class="form-control" value="{{ old('max_uses', $v->max_uses ?? '') }}" placeholder="Để trống = không giới hạn">
        @if($errors->has('max_uses'))
            <p class="help-block">{{ $errors->first('max_uses') }}</p>
        @endif
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6 {{ $errors->has('valid_from') ? 'has-error' : '' }}">
        <label for="valid_from">Hiệu lực từ</label>
        <input type="text" id="valid_from" name="valid_from" class="form-control datetime" value="{{ old('valid_from', isset($v) && $v->valid_from ? $v->valid_from->format('Y-m-d H:i:s') : '') }}">
        @if($errors->has('valid_from'))
            <p class="help-block">{{ $errors->first('valid_from') }}</p>
        @endif
    </div>
    <div class="form-group col-md-6 {{ $errors->has('valid_until') ? 'has-error' : '' }}">
        <label for="valid_until">Hiệu lực đến</label>
        <input type="text" id="valid_until" name="valid_until" class="form-control datetime" value="{{ old('valid_until', isset($v) && $v->valid_until ? $v->valid_until->format('Y-m-d H:i:s') : '') }}">
        @if($errors->has('valid_until'))
            <p class="help-block">{{ $errors->first('valid_until') }}</p>
        @endif
    </div>
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
    <label for="description">Mô tả ưu đãi</label>
    <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $v->description ?? '') }}</textarea>
    <p class="helper-block">Nội dung hiển thị cho khách khi họ nhập mã voucher.</p>
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="is_single_use" id="is_single_use" value="1" {{ old('is_single_use', $v->is_single_use ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_single_use">Mỗi người chỉ dùng 1 lần</label>
        </div>
    </div>
    <div class="form-group col-md-4">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="is_assignable" id="is_assignable" value="1" {{ old('is_assignable', $v->is_assignable ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_assignable">Có thể gán cho khách cụ thể</label>
        </div>
    </div>
    <div class="form-group col-md-4 {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">Trạng thái *</label>
        <select name="status" id="status" class="form-control">
            <option value="active" {{ old('status', $v->status ?? '') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
            <option value="inactive" {{ old('status', $v->status ?? '') == 'inactive' ? 'selected' : '' }}>Vô hiệu hóa</option>
            <option value="expired" {{ old('status', $v->status ?? '') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
        </select>
    </div>
</div>

<div class="form-group">
    <div class="alert alert-info" id="discountPreview" style="display:none;">
        <strong>Preview ưu đãi:</strong> <span id="discountPreviewText"></span>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
        function valueConfig() {
            var type = $('#type').val();
            var isValueVisible = (type === 'discount_percent' || type === 'discount_fixed');
            $('#valueField').toggle(isValueVisible);
            if (!isValueVisible) { $('#value').val(0); }
            if (type === 'discount_percent') { $('#valueUnit').text('%'); $('#valueHelper').text('VD: 20 = giảm 20% giá vé.'); }
            else if (type === 'discount_fixed') { $('#valueUnit').text('đ'); $('#valueHelper').text('VD: 100000 = giảm 100.000đ.'); }
            updatePreview();
        }
        function updatePreview() {
            var type = $('#type').val();
            var value = parseFloat($('#value').val() || 0);
            var label = '';
            if (type === 'discount_percent') label = 'Giảm ' + value + '%';
            else if (type === 'discount_fixed') label = 'Giảm ' + value.toLocaleString('vi-VN') + 'đ';
            else if (type === 'free_ticket') label = 'Vé miễn phí 100%';
            else if (type === 'gift') label = 'Quà tặng kèm vé';
            else if (type === 'priority_seat') label = 'Ưu tiên xếp chỗ VIP';
            $('#discountPreviewText').text(label);
            $('#discountPreview').toggle(label !== '');
        }
        $('#type').on('change', valueConfig);
        $('#value').on('input', updatePreview);
        valueConfig();
    });
</script>
@endsection