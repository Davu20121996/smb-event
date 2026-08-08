@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Edit Item
    </div>

    <div class="card-body">
        <form action="{{ route('admin.company-profile.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Section*</label>
                        <select name="section" class="form-control" required>
                            @foreach($sections as $key => $label)
                                <option value="{{ $key }}" {{ $item->section === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title*</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Category / Group</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $item->category) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Link (partners/clients)</label>
                        <input type="url" name="link" class="form-control" value="{{ old('link', $item->link) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order) }}" min="0">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Image / Logo</label>
                        <div class="needsclick dropzone" id="image-dropzone"></div>
                    </div>
                </div>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="Save">
                <a class="btn btn-default" href="{{ route('admin.company-profile.items', $item->section) }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.company-profile.items.storeMedia') }}',
    maxFilesize: 2,
    acceptedFiles: '.jpeg,.jpg,.png,.gif,.svg,.webp',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: { size: 2 },
    success: function (file, response) {
      $('form').find('input[name="image"]').remove()
      $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($item) && $item->image)
      var file = {!! json_encode($item->image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.thumbnail || file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response
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
