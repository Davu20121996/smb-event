@extends('layouts.admin')
@section('styles')
@parent
<link href="{{ asset('lib/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.landingPage.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.landing-pages.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.partials.multilang_hint', ['mlFields' => ['title', 'form_title', 'button_title', 'speaker_name', 'speaker_role', 'speaker_company', 'speaker_bio'], 'isCreate' => true])

            <h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.general') }}</strong></h5>

            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">{{ trans('cruds.landingPage.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($landingPage) ? $landingPage->title : '') }}" required>
                @if($errors->has('title'))
                    <p class="help-block">
                        {{ $errors->first('title') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.title_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                <label for="slug">{{ trans('cruds.landingPage.fields.slug') }}</label>
                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', isset($landingPage) ? $landingPage->slug : '') }}">
                @if($errors->has('slug'))
                    <p class="help-block">
                        {{ $errors->first('slug') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.slug_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                <label for="content">{{ trans('cruds.landingPage.fields.content') }}</label>
                <textarea id="content" name="content" class="form-control ckeditor">{{ old('content', isset($landingPage) ? $landingPage->content : '') }}</textarea>
                @if($errors->has('content'))
                    <p class="help-block">
                        {{ $errors->first('content') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.content_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('cover') ? 'has-error' : '' }}">
                <label for="cover">{{ trans('cruds.landingPage.fields.cover') }}</label>
                <div class="needsclick dropzone" id="cover-dropzone">

                </div>
                @if($errors->has('cover'))
                    <p class="help-block">
                        {{ $errors->first('cover') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.cover_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('thumbnail') ? 'has-error' : '' }}">
                <label for="thumbnail">{{ trans('cruds.landingPage.fields.thumbnail') }}</label>
                <div class="needsclick dropzone" id="thumbnail-dropzone">

                </div>
                @if($errors->has('thumbnail'))
                    <p class="help-block">
                        {{ $errors->first('thumbnail') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.thumbnail_helper') }}
                </p>
            </div>

            <hr>

            <h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.crm') }}</strong></h5>

            <div class="form-group {{ $errors->has('crm_tag') ? 'has-error' : '' }}">
                <label for="crm_tag">{{ trans('cruds.landingPage.fields.crm_tag') }}</label>
                <input type="text" id="crm_tag" name="crm_tag" class="form-control" value="{{ old('crm_tag', isset($landingPage) ? $landingPage->crm_tag : '') }}">
                @if($errors->has('crm_tag'))
                    <p class="help-block">
                        {{ $errors->first('crm_tag') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.crm_tag_helper') }}
                </p>
            </div>

            <hr>

            <h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.register') }}</strong></h5>

            <div class="form-group {{ $errors->has('form_title') ? 'has-error' : '' }}">
                <label for="form_title">{{ trans('cruds.landingPage.fields.form_title') }}</label>
                <input type="text" id="form_title" name="form_title" class="form-control" value="{{ old('form_title', isset($landingPage) ? $landingPage->form_title : '') }}">
                @if($errors->has('form_title'))
                    <p class="help-block">
                        {{ $errors->first('form_title') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.form_title_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('button_title') ? 'has-error' : '' }}">
                <label for="button_title">{{ trans('cruds.landingPage.fields.button_title') }}</label>
                <input type="text" id="button_title" name="button_title" class="form-control" value="{{ old('button_title', isset($landingPage) ? $landingPage->button_title : '') }}">
                @if($errors->has('button_title'))
                    <p class="help-block">
                        {{ $errors->first('button_title') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.button_title_helper') }}
                </p>
            </div>

            <hr>

            <h5 class="mt-3 mb-3"><strong>{{ trans('cruds.landingPage.sections.download') }}</strong></h5>

            <div class="form-group {{ $errors->has('pdf_enabled') ? 'has-error' : '' }}">
                <label for="pdf_enabled">{{ trans('cruds.landingPage.fields.pdf_enabled') }}</label>
                <input type="checkbox" id="pdf_enabled" name="pdf_enabled" value="1" {{ old('pdf_enabled', isset($landingPage) ? $landingPage->pdf_enabled : 0) ? 'checked' : '' }} onchange="toggleDownload(this.checked)">
                @if($errors->has('pdf_enabled'))
                    <p class="help-block">
                        {{ $errors->first('pdf_enabled') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.landingPage.fields.pdf_enabled_helper') }}
                </p>
            </div>

            <div id="download-fields" style="{{ (isset($landingPage) && $landingPage->pdf_enabled) ? '' : 'display:none;' }}">
                <div class="form-group {{ $errors->has('pdf_source') ? 'has-error' : '' }}">
                    <label for="pdf_source">{{ trans('cruds.landingPage.fields.pdf_source') }}</label>
                    <select name="pdf_source" id="pdf_source" class="form-control" onchange="togglePdfSource(this.value)">
                        <option value="upload" {{ (isset($landingPage) && $landingPage->pdf_source == 'upload') ? 'selected' : '' }}>{{ trans('cruds.landingPage.source_upload') }}</option>
                        <option value="url" {{ (isset($landingPage) && $landingPage->pdf_source == 'url') ? 'selected' : '' }}>{{ trans('cruds.landingPage.source_url') }}</option>
                    </select>
                    @if($errors->has('pdf_source'))
                        <p class="help-block">
                            {{ $errors->first('pdf_source') }}
                        </p>
                    @endif
                </div>
                <div id="pdf-upload-field" class="form-group {{ $errors->has('pdf_file') ? 'has-error' : '' }}">
                    <label for="pdf_file">{{ trans('cruds.landingPage.fields.pdf_file') }}</label>
                    <div class="needsclick dropzone" id="pdf-dropzone">

                    </div>
                    <p class="help-block"><i class="fa fa-info-circle"></i> {{ trans('cruds.landingPage.fields.pdf_file_help') }}</p>
                    @if($errors->has('pdf_file'))
                        <p class="help-block">
                            {{ $errors->first('pdf_file') }}
                        </p>
                    @endif
                </div>
                <div id="pdf-url-field" class="form-group {{ $errors->has('pdf_url') ? 'has-error' : '' }}" style="display:none;">
                    <label for="pdf_url">{{ trans('cruds.landingPage.fields.pdf_url') }}</label>
                    <input type="text" id="pdf_url" name="pdf_url" class="form-control" value="{{ old('pdf_url', isset($landingPage) ? $landingPage->pdf_url : '') }}">
                    @if($errors->has('pdf_url'))
                        <p class="help-block">
                            {{ $errors->first('pdf_url') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('download_title') ? 'has-error' : '' }}">
                    <label for="download_title">{{ trans('cruds.landingPage.fields.download_title') }}</label>
                    <input type="text" id="download_title" name="download_title" class="form-control" value="{{ old('download_title', isset($landingPage) ? $landingPage->download_title : '') }}">
                    @if($errors->has('download_title'))
                        <p class="help-block">
                            {{ $errors->first('download_title') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('download_button_title') ? 'has-error' : '' }}">
                    <label for="download_button_title">{{ trans('cruds.landingPage.fields.download_button_title') }}</label>
                    <input type="text" id="download_button_title" name="download_button_title" class="form-control" value="{{ old('download_button_title', isset($landingPage) ? $landingPage->download_button_title : '') }}">
                    @if($errors->has('download_button_title'))
                        <p class="help-block">
                            {{ $errors->first('download_button_title') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('report_url') ? 'has-error' : '' }}">
                    <label for="report_url">{{ trans('cruds.landingPage.fields.report_url') }}</label>
                    <input type="text" id="report_url" name="report_url" class="form-control" value="{{ old('report_url', isset($landingPage) ? $landingPage->report_url : '') }}">
                    @if($errors->has('report_url'))
                        <p class="help-block">
                            {{ $errors->first('report_url') }}
                        </p>
                    @endif
                </div>
            </div>

            <hr>

            @include('admin.landing-pages._landing-config')

            <hr>

            <div class="form-group {{ $errors->has('is_published') ? 'has-error' : '' }}">
                <label for="is_published">{{ trans('cruds.landingPage.fields.is_published') }}</label>
                <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', isset($landingPage) ? $landingPage->is_published : 1) ? 'checked' : '' }}>
                @if($errors->has('is_published'))
                    <p class="help-block">
                        {{ $errors->first('is_published') }}
                    </p>
                @endif
            </div>
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
    Dropzone.options.coverDropzone = {
    url: '{{ route('admin.landing-pages.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif,.webp',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="cover"]').remove()
      $('form').append('<input type="hidden" name="cover" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="cover"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($landingPage) && $landingPage->cover)
      var file = {!! json_encode($landingPage->cover) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="cover" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script>
    Dropzone.options.thumbnailDropzone = {
    url: '{{ route('admin.landing-pages.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif,.webp',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="thumbnail"]').remove()
      $('form').append('<input type="hidden" name="thumbnail" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="thumbnail"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($landingPage) && $landingPage->thumbnail)
      var file = {!! json_encode($landingPage->thumbnail) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="thumbnail" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script>
    Dropzone.options.pdfDropzone = {
    url: '{{ route('admin.landing-pages.storeMedia') }}',
    maxFilesize: 10, // MB
    acceptedFiles: '.pdf',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').find('input[name="pdf_file"]').remove()
      $('form').append('<input type="hidden" name="pdf_file" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="pdf_file"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($landingPage) && $landingPage->pdf_file)
      var file = {!! json_encode($landingPage->pdf_file) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="pdf_file" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script>
    $(document).ready(function () {
  var pdfEnabled = document.getElementById('pdf_enabled')
  if (pdfEnabled) {
    toggleDownload(pdfEnabled.checked)
  }
  var pdfSource = document.getElementById('pdf_source')
  if (pdfSource) {
    togglePdfSource(pdfSource.value)
  }
});

function toggleDownload(checked) {
  document.getElementById('download-fields').style.display = checked ? '' : 'none';
  if (checked) {
    var source = document.getElementById('pdf_source')
    togglePdfSource(source ? source.value : 'upload')
  }
}

function togglePdfSource(value) {
  document.getElementById('pdf-upload-field').style.display = value === 'upload' ? '' : 'none';
  document.getElementById('pdf-url-field').style.display = value === 'url' ? '' : 'none';
}
</script>
<script>
    Dropzone.options.speakerAvatarDropzone = {
    url: '{{ route('admin.landing-pages.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif,.webp',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="speaker_avatar"]').remove()
      $('form').append('<input type="hidden" name="speaker_avatar" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="speaker_avatar"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($landingPage) && $landingPage->speaker_avatar)
      var file = {!! json_encode($landingPage->speaker_avatar) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="speaker_avatar" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script>
@php $landingIcons = include resource_path('views/admin/landing-pages/_icons.php'); @endphp
$(document).ready(function () {
  var landingIcons = {!! json_encode($landingIcons) !!};
  var iconOptions = '';
  Object.keys(landingIcons).forEach(function (cls) {
    iconOptions += '<option value="' + cls + '">' + landingIcons[cls] + '</option>';
  });

  function iconSelectTemplate(state) {
    if (!state.id) return state.text;
    return $('<span class="icon-select-opt"><i class="fa ' + state.id + '"></i> ' + state.text + '</span>');
  }
  function initIconSelects(scope) {
    var $sel = scope ? $(scope).find('.icon-select') : $('.icon-select');
    $sel.select2({
      templateResult: iconSelectTemplate,
      templateSelection: iconSelectTemplate,
      width: '100%'
    });
  }

  var kbCounter = 0;
  $('#key-benefits-rows .key-benefit-row').each(function () {
    var idx = parseInt($(this).data('index'), 10);
    if (!isNaN(idx) && idx > kbCounter) kbCounter = idx;
  });
  initIconSelects();

  $('#add-key-benefit-row').on('click', function () {
    kbCounter++;
    var html = '<div class="row key-benefit-row mb-2">' +
      '<div class="col-md-2"><select name="key_benefits[' + kbCounter + '][icon]" class="form-control icon-select">' + iconOptions + '</select></div>' +
      '<div class="col-md-3"><input type="text" name="key_benefits[' + kbCounter + '][title]" class="form-control" placeholder="{{ trans('cruds.landingPage.fields.kb_title_ph') }}"></div>' +
      '<div class="col-md-6"><input type="text" name="key_benefits[' + kbCounter + '][description]" class="form-control" placeholder="{{ trans('cruds.landingPage.fields.kb_desc_ph') }}"></div>' +
      '<div class="col-md-1 text-right"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-minus"></i></button></div>' +
      '</div>';
    $('#key-benefits-rows').append(html);
    initIconSelects($('#key-benefits-rows .key-benefit-row').last());
  });

  var agCounter = 0;
  $('#agenda-rows .agenda-row').each(function () {
    var idx = parseInt($(this).data('index'), 10);
    if (!isNaN(idx) && idx > agCounter) agCounter = idx;
  });
  $('#add-agenda-row').on('click', function () {
    agCounter++;
    var html = '<div class="agenda-row card mb-2 p-2"><div class="row">' +
      '<div class="col-md-2"><input type="text" name="agenda[' + agCounter + '][time]" class="form-control" placeholder="{{ trans('cruds.landingPage.fields.ag_time_ph') }}"></div>' +
      '<div class="col-md-3"><input type="text" name="agenda[' + agCounter + '][title]" class="form-control" placeholder="{{ trans('cruds.landingPage.fields.ag_title_ph') }}"></div>' +
      '<div class="col-md-5"><input type="text" name="agenda[' + agCounter + '][description]" class="form-control" placeholder="{{ trans('cruds.landingPage.fields.ag_desc_ph') }}"></div>' +
      '<div class="col-md-1"><input type="text" name="agenda[' + agCounter + '][speaker]" class="form-control" placeholder="{{ trans('cruds.landingPage.fields.ag_speaker_ph') }}"></div>' +
      '<div class="col-md-1 text-right"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-minus"></i></button></div>' +
      '</div></div>';
    $('#agenda-rows').append(html);
  });

  $(document).on('click', '.remove-row', function () {
    $(this).closest('.key-benefit-row, .agenda-row').remove();
  });
});
</script>
@endsection
