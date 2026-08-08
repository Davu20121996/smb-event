<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name">{{ trans('cruds.event.fields.name') }}*</label>
    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($event) ? $event->name : '') }}" required>
    @if($errors->has('name'))
        <p class="help-block">
            {{ $errors->first('name') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.name_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
    <label for="slug">{{ trans('cruds.event.fields.slug') }}</label>
    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', isset($event) ? $event->slug : '') }}">
    @if($errors->has('slug'))
        <p class="help-block">
            {{ $errors->first('slug') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.slug_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
    <label for="description">{{ trans('cruds.event.fields.description') }}</label>
    <textarea id="description" name="description" class="form-control ">{{ old('description', isset($event) ? $event->description : '') }}</textarea>
    @if($errors->has('description'))
        <p class="help-block">
            {{ $errors->first('description') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.description_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('start_date') ? 'has-error' : '' }}">
    <label for="start_date">{{ trans('cruds.event.fields.start_date') }}</label>
    <input type="text" id="start_date" name="start_date" class="form-control datepicker" value="{{ old('start_date', isset($event) ? $event->start_date : '') }}">
    @if($errors->has('start_date'))
        <p class="help-block">
            {{ $errors->first('start_date') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.start_date_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('end_date') ? 'has-error' : '' }}">
    <label for="end_date">{{ trans('cruds.event.fields.end_date') }}</label>
    <input type="text" id="end_date" name="end_date" class="form-control datepicker" value="{{ old('end_date', isset($event) ? $event->end_date : '') }}">
    @if($errors->has('end_date'))
        <p class="help-block">
            {{ $errors->first('end_date') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.end_date_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('is_active') ? 'has-error' : '' }}">
    <label for="is_active">{{ trans('cruds.event.fields.is_active') }}</label>
    <select name="is_active" id="is_active" class="form-control">
        <option value="1" {{ old('is_active', isset($event) ? (int) $event->is_active : 1) == 1 ? 'selected' : '' }}>{{ trans('global.yes') }}</option>
        <option value="0" {{ old('is_active', isset($event) ? (int) $event->is_active : 1) == 0 ? 'selected' : '' }}>{{ trans('global.no') }}</option>
    </select>
    @if($errors->has('is_active'))
        <p class="help-block">
            {{ $errors->first('is_active') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.is_active_helper') }}
    </p>
</div>
<h4 class="mt-4">Giới thiệu sự kiện</h4>
<hr>
<div class="form-group {{ $errors->has('about_description') ? 'has-error' : '' }}">
    <label for="about_description">Giới thiệu về sự kiện</label>
    <textarea id="about_description" name="about_description" class="form-control ">{{ old('about_description', isset($event) ? $event->about_description : '') }}</textarea>
    @if($errors->has('about_description'))
        <p class="help-block">
            {{ $errors->first('about_description') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.about_description_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('about_where') ? 'has-error' : '' }}">
    <label for="about_where">Địa điểm</label>
    <input type="text" id="about_where" name="about_where" class="form-control" value="{{ old('about_where', isset($event) ? $event->about_where : '') }}">
    @if($errors->has('about_where'))
        <p class="help-block">
            {{ $errors->first('about_where') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.about_where_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('about_when') ? 'has-error' : '' }}">
    <label for="about_when">Thời gian</label>
    <input type="text" id="about_when" name="about_when" class="form-control" value="{{ old('about_when', isset($event) ? $event->about_when : '') }}">
    @if($errors->has('about_when'))
        <p class="help-block">
            {{ $errors->first('about_when') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.about_when_helper') }}
    </p>
</div>
<h4 class="mt-4">{{ trans('cruds.event.sections.countdown') }}</h4>
<hr>
<div class="form-group {{ $errors->has('countdown_enabled') ? 'has-error' : '' }}">
    <label for="countdown_enabled">{{ trans('cruds.event.fields.countdown_enabled') }}</label>
    <select name="countdown_enabled" id="countdown_enabled" class="form-control">
        <option value="1" {{ old('countdown_enabled', isset($event) ? (int) $event->countdown_enabled : 0) == 1 ? 'selected' : '' }}>{{ trans('global.yes') }}</option>
        <option value="0" {{ old('countdown_enabled', isset($event) ? (int) $event->countdown_enabled : 0) == 0 ? 'selected' : '' }}>{{ trans('global.no') }}</option>
    </select>
    @if($errors->has('countdown_enabled'))
        <p class="help-block">
            {{ $errors->first('countdown_enabled') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.countdown_enabled_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('registration_deadline') ? 'has-error' : '' }}">
    <label for="registration_deadline">{{ trans('cruds.event.fields.registration_deadline') }}</label>
    @php
        $deadline = isset($event) ? $event->registration_deadline : null;
        $deadlineValue = $deadline
            ? (is_object($deadline) && method_exists($deadline, 'format') ? $deadline->format('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($deadline)))
            : '';
    @endphp
    <input type="text" id="registration_deadline" name="registration_deadline" class="form-control datetimepicker" value="{{ old('registration_deadline', $deadlineValue) }}">
    @if($errors->has('registration_deadline'))
        <p class="help-block">
            {{ $errors->first('registration_deadline') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.registration_deadline_helper') }}
    </p>
</div>
<h4 class="mt-4">{{ trans('cruds.event.sections.seo') }}</h4>
<hr>
<div class="form-group {{ $errors->has('meta_title') ? 'has-error' : '' }}">
    <label for="meta_title">{{ trans('cruds.event.fields.meta_title') }}</label>
    <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title', isset($event) ? $event->meta_title : '') }}">
    @if($errors->has('meta_title'))
        <p class="help-block">
            {{ $errors->first('meta_title') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.meta_title_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('meta_description') ? 'has-error' : '' }}">
    <label for="meta_description">{{ trans('cruds.event.fields.meta_description') }}</label>
    <textarea id="meta_description" name="meta_description" class="form-control ">{{ old('meta_description', isset($event) ? $event->meta_description : '') }}</textarea>
    @if($errors->has('meta_description'))
        <p class="help-block">
            {{ $errors->first('meta_description') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.meta_description_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('favicon_url') ? 'has-error' : '' }}">
    <label for="favicon_url">{{ trans('cruds.event.fields.favicon_url') }}</label>
    <input type="text" id="favicon_url" name="favicon_url" class="form-control" value="{{ old('favicon_url', isset($event) ? $event->favicon_url : '') }}">
    @if($errors->has('favicon_url'))
        <p class="help-block">
            {{ $errors->first('favicon_url') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.favicon_url_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('og_image') ? 'has-error' : '' }}">
    <label for="og_image">{{ trans('cruds.event.fields.og_image') }}</label>
    <input type="text" id="og_image" name="og_image" class="form-control" value="{{ old('og_image', isset($event) ? $event->og_image : '') }}">
    @if($errors->has('og_image'))
        <p class="help-block">
            {{ $errors->first('og_image') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.og_image_helper') }}
    </p>
</div>
<h4 class="mt-4">{{ trans('cruds.event.sections.thankyou') }}</h4>
<hr>
<div class="form-group {{ $errors->has('calendar_enabled') ? 'has-error' : '' }}">
    <label for="calendar_enabled">{{ trans('cruds.event.fields.calendar_enabled') }}</label>
    <select name="calendar_enabled" id="calendar_enabled" class="form-control">
        <option value="1" {{ old('calendar_enabled', isset($event) ? (int) $event->calendar_enabled : 1) == 1 ? 'selected' : '' }}>{{ trans('global.yes') }}</option>
        <option value="0" {{ old('calendar_enabled', isset($event) ? (int) $event->calendar_enabled : 1) == 0 ? 'selected' : '' }}>{{ trans('global.no') }}</option>
    </select>
    @if($errors->has('calendar_enabled'))
        <p class="help-block">
            {{ $errors->first('calendar_enabled') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.calendar_enabled_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('zalo_url') ? 'has-error' : '' }}">
    <label for="zalo_url">{{ trans('cruds.event.fields.zalo_url') }}</label>
    <input type="text" id="zalo_url" name="zalo_url" class="form-control" value="{{ old('zalo_url', isset($event) ? $event->zalo_url : '') }}">
    @if($errors->has('zalo_url'))
        <p class="help-block">
            {{ $errors->first('zalo_url') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.zalo_url_helper') }}
    </p>
</div>
<div class="form-group {{ $errors->has('fanpage_url') ? 'has-error' : '' }}">
    <label for="fanpage_url">{{ trans('cruds.event.fields.fanpage_url') }}</label>
    <input type="text" id="fanpage_url" name="fanpage_url" class="form-control" value="{{ old('fanpage_url', isset($event) ? $event->fanpage_url : '') }}">
    @if($errors->has('fanpage_url'))
        <p class="help-block">
            {{ $errors->first('fanpage_url') }}
        </p>
    @endif
    <p class="helper-block">
        {{ trans('cruds.event.fields.fanpage_url_helper') }}
    </p>
</div>
<h4 class="mt-4">Hiển thị section trên trang sự kiện</h4>
<hr>
@foreach([
    'show_gallery'  => 'Hiển thị Thư viện ảnh',
    'show_sponsors' => 'Hiển thị Nhà tài trợ',
    'show_tickets'  => 'Hiển thị Mua vé',
] as $field => $label)
    <div class="form-group">
        <label for="{{ $field }}">{{ $label }}</label>
        <select name="{{ $field }}" id="{{ $field }}" class="form-control">
            <option value="1" {{ old($field, isset($event) ? (int) $event->$field : 1) == 1 ? 'selected' : '' }}>{{ trans('global.yes') }}</option>
            <option value="0" {{ old($field, isset($event) ? (int) $event->$field : 1) == 0 ? 'selected' : '' }}>{{ trans('global.no') }}</option>
        </select>
    </div>
@endforeach
<h4 class="mt-4"><i class="fa fa-image"></i> Ảnh nền sự kiện (PC & Mobile)</h4>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="card border-primary mb-3">
            <div class="card-header bg-primary text-white">
                <i class="fa fa-desktop"></i> Ảnh nền PC (màn hình lớn)
            </div>
            <div class="card-body text-center">
                @if(isset($event) && $event->pc_bg_image_url)
                    <div class="mb-3 position-relative d-inline-block" id="pc-bg-preview-wrap">
                        <img src="{{ $event->pc_bg_image_url }}" alt="PC Background" class="img-fluid rounded" style="max-height: 300px; border: 2px solid #dee2e6;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px; opacity: .9;" onclick="removeBgImage('pc_bg')" title="Xóa ảnh">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @else
                    <div class="p-4 mb-3 rounded" style="border: 2px dashed #ccc; background: #f8f9fa;">
                        <i class="fa fa-cloud-upload fa-3x text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Chưa có ảnh nền PC</p>
                    </div>
                @endif
                <div class="form-group {{ $errors->has('pc_bg_image') ? 'has-error' : '' }} text-left">
                    <input type="file" id="pc_bg_image" name="pc_bg_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                    @if($errors->has('pc_bg_image'))
                        <p class="help-block text-danger">{{ $errors->first('pc_bg_image') }}</p>
                    @endif
                </div>
                <small class="text-muted"><i class="fa fa-info-circle"></i> Khuyến nghị: <strong>1920×1080px</strong> | JPG, PNG, WebP | Tối đa 5MB</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-success mb-3">
            <div class="card-header bg-success text-white">
                <i class="fa fa-mobile fa-lg"></i> Ảnh nền Mobile (điện thoại)
            </div>
            <div class="card-body text-center">
                @if(isset($event) && $event->mobile_bg_image_url)
                    <div class="mb-3 position-relative d-inline-block" id="mobile-bg-preview-wrap">
                        <img src="{{ $event->mobile_bg_image_url }}" alt="Mobile Background" class="img-fluid rounded" style="max-height: 300px; border: 2px solid #dee2e6;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px; opacity: .9;" onclick="removeBgImage('mobile_bg')" title="Xóa ảnh">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @else
                    <div class="p-4 mb-3 rounded" style="border: 2px dashed #ccc; background: #f8f9fa;">
                        <i class="fa fa-cloud-upload fa-3x text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Chưa có ảnh nền Mobile</p>
                    </div>
                @endif
                <div class="form-group {{ $errors->has('mobile_bg_image') ? 'has-error' : '' }} text-left">
                    <input type="file" id="mobile_bg_image" name="mobile_bg_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                    @if($errors->has('mobile_bg_image'))
                        <p class="help-block text-danger">{{ $errors->first('mobile_bg_image') }}</p>
                    @endif
                </div>
                <small class="text-muted"><i class="fa fa-info-circle"></i> Khuyến nghị: <strong>750×1334px</strong> | JPG, PNG, WebP | Tối đa 5MB</small>
            </div>
        </div>
    </div>
</div>
@if(isset($event))
<script>
function removeBgImage(collection) {
    if (!confirm('Bạn chắc chắn muốn xóa ảnh nền này?')) return;
    $.ajax({
        url: '{{ route("admin.events.remove-bg", $event->id) }}',
        type: 'DELETE',
        data: { collection: collection },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function () { location.reload(); },
        error: function (xhr) { alert('Lỗi xóa ảnh: ' + xhr.status); }
    });
}
</script>
@endif
