@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Company Profile &raquo; Section Items
    </div>

    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <ul class="nav nav-pills mb-3">
            <li class="nav-item">
                <a class="nav-link {{ $section === null ? 'active' : '' }}" href="{{ route('admin.company-profile.items') }}">All</a>
            </li>
            @foreach($sections as $key => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $section === $key ? 'active' : '' }}" href="{{ route('admin.company-profile.items', $key) }}">{{ $label }}</a>
                </li>
            @endforeach
        </ul>

        <div class="mb-3">
            <a class="btn btn-success" href="#" data-toggle="collapse" data-target="#addItemForm" aria-expanded="false">
                Add Item
            </a>
        </div>

        <div class="collapse mb-4" id="addItemForm">
            <div class="card card-body border">
                <form action="{{ route('admin.company-profile.items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Section*</label>
                                <select name="section" class="form-control" required>
                                    @foreach($sections as $key => $label)
                                        <option value="{{ $key }}" {{ $section === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Title*</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Category / Group</label>
                                <input type="text" name="category" class="form-control" placeholder="e.g. Software Engineering">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Link (partners/clients)</label>
                                <input type="url" name="link" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Image / Logo</label>
                                <div class="needsclick dropzone" id="image-dropzone"></div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">Add Item</button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Sort</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $sections[$item->section] ?? $item->section }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->description, 60) }}</td>
                            <td>
                                @if($item->image)
                                    <img src="{{ $item->image->thumbnail }}" alt="" style="max-width:40px;">
                                @endif
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ route('admin.company-profile.items.edit', $item->id) }}">Edit</a>
                                <a class="btn btn-xs btn-outline-primary" href="{{ route('admin.company-profile.items.up', $item->id) }}">Up</a>
                                <a class="btn btn-xs btn-outline-primary" href="{{ route('admin.company-profile.items.down', $item->id) }}">Down</a>
                                <form action="{{ route('admin.company-profile.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
    }
}
</script>
@endsection
