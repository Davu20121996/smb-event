@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.post.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.posts.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.partials.multilang_hint', ['mlFields' => ['title', 'tag', 'excerpt'], 'isCreate' => true])
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">{{ trans('cruds.post.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($post) ? $post->title : '') }}" required>
                @if($errors->has('title'))
                    <p class="help-block">
                        {{ $errors->first('title') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.post.fields.title_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                <label for="slug">{{ trans('cruds.post.fields.slug') }}</label>
                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', isset($post) ? $post->slug : '') }}">
                @if($errors->has('slug'))
                    <p class="help-block">
                        {{ $errors->first('slug') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.post.fields.slug_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('tag') ? 'has-error' : '' }}">
                <label for="tag">{{ trans('cruds.post.fields.tag') }}</label>
                <input type="text" id="tag" name="tag" class="form-control" value="{{ old('tag', isset($post) ? $post->tag : '') }}">
                @if($errors->has('tag'))
                    <p class="help-block">
                        {{ $errors->first('tag') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.post.fields.tag_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('excerpt') ? 'has-error' : '' }}">
                <label for="excerpt">{{ trans('cruds.post.fields.excerpt') }}</label>
                <textarea id="excerpt" name="excerpt" class="form-control ">{{ old('excerpt', isset($post) ? $post->excerpt : '') }}</textarea>
                @if($errors->has('excerpt'))
                    <p class="help-block">
                        {{ $errors->first('excerpt') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.post.fields.excerpt_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                <label for="content">{{ trans('cruds.post.fields.content') }}</label>
                <textarea id="content" name="content" class="form-control ckeditor">{{ old('content', isset($post) ? $post->content : '') }}</textarea>
                @if($errors->has('content'))
                    <p class="help-block">
                        {{ $errors->first('content') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.post.fields.content_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('cover') ? 'has-error' : '' }}">
                <label for="cover">{{ trans('cruds.post.fields.cover') }}</label>
                <div class="needsclick dropzone" id="cover-dropzone">

                </div>
                @if($errors->has('cover'))
                    <p class="help-block">
                        {{ $errors->first('cover') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.post.fields.cover_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('thumbnail') ? 'has-error' : '' }}">
                <label for="thumbnail">{{ trans('cruds.post.fields.thumbnail') }}</label>
                <div class="needsclick dropzone" id="thumbnail-dropzone">

                </div>
                @if($errors->has('thumbnail'))
                    <p class="help-block">
                        {{ $errors->first('thumbnail') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.post.fields.thumbnail_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('is_published') ? 'has-error' : '' }}">
                <label for="is_published">{{ trans('cruds.post.fields.is_published') }}</label>
                <select name="is_published" id="is_published" class="form-control">
                    <option value="1" {{ (isset($post) && $post->is_published) ? 'selected' : '' }}>{{ trans('global.yes') }}</option>
                    <option value="0" {{ isset($post) && !$post->is_published ? 'selected' : '' }}>{{ trans('global.no') }}</option>
                </select>
                @if($errors->has('is_published'))
                    <p class="help-block">
                        {{ $errors->first('is_published') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.post.fields.is_published_helper') }}
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
@parent
<script>
    Dropzone.options.coverDropzone = {
    url: '{{ route('admin.posts.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
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
@if(isset($post) && $post->cover)
      var file = {!! json_encode($post->cover) !!}
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
    url: '{{ route('admin.posts.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
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
@if(isset($post) && $post->thumbnail)
      var file = {!! json_encode($post->thumbnail) !!}
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
@endsection
