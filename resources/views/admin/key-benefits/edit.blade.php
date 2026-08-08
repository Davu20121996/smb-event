@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.keyBenefit.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.key-benefits.update", [$keyBenefit->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.partials.multilang_hint', ['mlFields' => ['title', 'description'], 'isCreate' => false])
            <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                <label for="icon">{{ trans('cruds.keyBenefit.fields.icon') }}</label>
                <div class="needsclick dropzone" id="icon-dropzone">

                </div>
                @if($errors->has('icon'))
                    <p class="help-block">
                        {{ $errors->first('icon') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.keyBenefit.fields.icon_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">{{ trans('cruds.keyBenefit.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($keyBenefit) ? $keyBenefit->title : '') }}" required>
                @if($errors->has('title'))
                    <p class="help-block">
                        {{ $errors->first('title') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.keyBenefit.fields.title_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">{{ trans('cruds.keyBenefit.fields.description') }}</label>
                <textarea id="description" name="description" class="form-control " rows="4">{{ old('description', isset($keyBenefit) ? $keyBenefit->description : '') }}</textarea>
                @if($errors->has('description'))
                    <p class="help-block">
                        {{ $errors->first('description') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.keyBenefit.fields.description_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('sort_order') ? 'has-error' : '' }}">
                <label for="sort_order">{{ trans('cruds.keyBenefit.fields.sort_order') }}</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', isset($keyBenefit) ? $keyBenefit->sort_order : 0) }}" step="1">
                @if($errors->has('sort_order'))
                    <p class="help-block">
                        {{ $errors->first('sort_order') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.keyBenefit.fields.sort_order_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.iconDropzone = {
    url: '{{ route("admin.key-benefits.storeMedia") }}',
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
      $('form').find('input[name="icon"]').remove()
      $('form').append('<input type="hidden" name="icon" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="icon"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
init: function () {
        @if(isset($keyBenefit->icon_image_url))
        var file = {
            name: '',
            url: '{{ $keyBenefit->icon_image_url }}',
            size: 0
        };
        this.options.addedfile.call(this, file);
        this.options.thumbnail.call(this, file, file.url);
        file.previewElement.classList.add('dz-complete');
        this.options.maxFiles = this.options.maxFiles - 1;
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
@stop
