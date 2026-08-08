@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Company Profile
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="company-tab" data-toggle="tab" href="#company" role="tab">Company</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="mission-tab" data-toggle="tab" href="#mission" role="tab">Vision & Mission</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sections-tab" data-toggle="tab" href="#sections" role="tab">Section Titles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="letter-tab" data-toggle="tab" href="#letter" role="tab">Letter & Thanks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab">Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="footer-tab" data-toggle="tab" href="#footer" role="tab">Footer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="projects-tab" data-toggle="tab" href="#projects" role="tab">Projects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.company-profile.items') }}">Section Items</a>
            </li>
        </ul>

        <div class="mt-3">
            <a href="{{ route('admin.company-profile.items') }}" class="btn btn-sm btn-info">
                Manage lists (values, services, solutions, process, tech, team, models, partners, clients, commitments, warranty)
            </a>
        </div>

        <form action="{{ route('admin.company-profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="tab-content pt-3">
                <div class="tab-pane active" id="company" role="tabpanel">
                    <div class="form-group">
                        <label for="company_title">Company Title</label>
                        <input type="text" id="company_title" name="company_title" class="form-control" value="{{ $settings['company_title'] ?? '' }}">
                        <p class="helper-block">Hero title shown on the home page.</p>
                    </div>
                    <div class="form-group">
                        <label for="company_subtitle">Company Subtitle</label>
                        <input type="text" id="company_subtitle" name="company_subtitle" class="form-control" value="{{ $settings['company_subtitle'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="company_slogan">Company Slogan</label>
                        <input type="text" id="company_slogan" name="company_slogan" class="form-control" value="{{ $settings['company_slogan'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="company_about">About Us (Giới thiệu SMB+)</label>
                        <textarea id="company_about" name="company_about" class="form-control ckeditor">{{ $settings['company_about'] ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="company_youtube_link">Youtube Link</label>
                        <input type="url" id="company_youtube_link" name="company_youtube_link" class="form-control" value="{{ $settings['company_youtube_link'] ?? '' }}">
                    </div>
                </div>

                <div class="tab-pane" id="mission" role="tabpanel">
                    <div class="form-group">
                        <label for="company_vision">Tầm nhìn (Vision)</label>
                        <textarea id="company_vision" name="company_vision" class="form-control ckeditor">{{ $settings['company_vision'] ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="company_mission">Sứ mệnh (Mission)</label>
                        <textarea id="company_mission" name="company_mission" class="form-control ckeditor">{{ $settings['company_mission'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="tab-pane" id="sections" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Section</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sections as $section)
                                    <tr>
                                        <td>{{ ucwords(str_replace('_', ' ', str_replace('sec_', '', $section))) }}</td>
                                        <td>
                                            <input type="text" name="{{ $section }}_title" class="form-control" value="{{ $settings[$section . '_title'] ?? '' }}">
                                        </td>
                                        <td>
                                            <input type="text" name="{{ $section }}_subtitle" class="form-control" value="{{ $settings[$section . '_subtitle'] ?? '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane" id="letter" role="tabpanel">
                    <div class="form-group">
                        <label for="company_letter">Thư ngỏ từ Ban Giám đốc</label>
                        <textarea id="company_letter" name="company_letter" class="form-control ckeditor">{{ $settings['company_letter'] ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="company_thanks">Lời cảm ơn</label>
                        <textarea id="company_thanks" name="company_thanks" class="form-control ckeditor">{{ $settings['company_thanks'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="tab-pane" id="contact" role="tabpanel">
                    <div class="form-group">
                        <label for="contact_address">Address</label>
                        <input type="text" id="contact_address" name="contact_address" class="form-control" value="{{ $settings['contact_address'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="contact_phone">Phone Number</label>
                        <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="contact_email">Email</label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="contact_website">Website</label>
                        <input type="url" id="contact_website" name="contact_website" class="form-control" value="{{ $settings['contact_website'] ?? '' }}">
                    </div>
                </div>

                <div class="tab-pane" id="footer" role="tabpanel">
                    <div class="form-group">
                        <label for="footer_description">Footer Description</label>
                        <textarea id="footer_description" name="footer_description" class="form-control">{{ $settings['footer_description'] ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="footer_address">Footer Address</label>
                        <textarea id="footer_address" name="footer_address" class="form-control">{{ $settings['footer_address'] ?? '' }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_twitter">Twitter URL</label>
                                <input type="text" id="footer_twitter" name="footer_twitter" class="form-control" value="{{ $settings['footer_twitter'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_facebook">Facebook URL</label>
                                <input type="text" id="footer_facebook" name="footer_facebook" class="form-control" value="{{ $settings['footer_facebook'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_instagram">Instagram URL</label>
                                <input type="text" id="footer_instagram" name="footer_instagram" class="form-control" value="{{ $settings['footer_instagram'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_googleplus">Google Plus URL</label>
                                <input type="text" id="footer_googleplus" name="footer_googleplus" class="form-control" value="{{ $settings['footer_googleplus'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer_linkedin">LinkedIn URL</label>
                                <input type="text" id="footer_linkedin" name="footer_linkedin" class="form-control" value="{{ $settings['footer_linkedin'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="projects" role="tabpanel">
                    <div class="mb-3">
                        <a class="btn btn-success" href="{{ route('admin.posts.create') }}">
                            Add Project
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Excerpt</th>
                                    <th>Published</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->excerpt }}</td>
                                        <td>{{ $post->is_published ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.posts.edit', $post->id) }}">Edit</a>
                                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No projects yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <input class="btn btn-danger" type="submit" value="Save">
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@parent
@endsection
