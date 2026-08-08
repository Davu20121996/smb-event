@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.landingPage.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.id') }}
                        </th>
                        <td>
                            {{ $landingPage->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.title') }}
                        </th>
                        <td>
                            {{ $landingPage->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.slug') }}
                        </th>
                        <td>
                            <a href="{{ route('share.show', $landingPage->slug) }}" target="_blank">{{ $landingPage->slug }}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.content') }}
                        </th>
                        <td>
                            {!! $landingPage->content !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.cover') }}
                        </th>
                        <td>
                            @if($landingPage->cover)
                                <a href="{{ $landingPage->cover->getUrl() }}" target="_blank">
                                    <img src="{{ $landingPage->cover->getUrl('thumb') }}" width="50px" height="50px">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.thumbnail') }}
                        </th>
                        <td>
                            @if($landingPage->thumbnail)
                                <a href="{{ $landingPage->thumbnail->getUrl() }}" target="_blank">
                                    <img src="{{ $landingPage->thumbnail->getUrl('thumb') }}" width="50px" height="50px">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.crm_tag') }}
                        </th>
                        <td>
                            {{ $landingPage->crm_tag ?: $landingPage->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.form_title') }}
                        </th>
                        <td>
                            {{ $landingPage->form_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.button_title') }}
                        </th>
                        <td>
                            {{ $landingPage->button_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.pdf_enabled') }}
                        </th>
                        <td>
                            {{ $landingPage->pdf_enabled ? trans('global.yes') : trans('global.no') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.pdf_source') }}
                        </th>
                        <td>
                            @if($landingPage->pdf_enabled)
                                {{ $landingPage->pdf_source == 'url' ? trans('cruds.landingPage.source_url') : trans('cruds.landingPage.source_upload') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.pdf_file') }}
                        </th>
                        <td>
                            @if($landingPage->pdf_file)
                                <a href="{{ $landingPage->pdf_file->getUrl() }}" target="_blank">{{ $landingPage->pdf_file->file_name }}</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.pdf_url') }}
                        </th>
                        <td>
                            {{ $landingPage->pdf_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.download_title') }}
                        </th>
                        <td>
                            {{ $landingPage->download_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.download_button_title') }}
                        </th>
                        <td>
                            {{ $landingPage->download_button_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.report_url') }}
                        </th>
                        <td>
                            {{ $landingPage->report_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.countdown_enabled') }}
                        </th>
                        <td>
                            {{ $landingPage->countdown_enabled ? trans('global.yes') : trans('global.no') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.registration_deadline') }}
                        </th>
                        <td>
                            {{ $landingPage->registration_deadline ? $landingPage->registration_deadline->format('d/m/Y H:i') : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.sections.key_benefits') }}
                        </th>
                        <td>
                            @if($landingPage->key_benefits)
                                <ul class="mb-0">
                                    @foreach($landingPage->key_benefits as $kb)
                                        <li><i class="fa {{ $kb['icon'] ?? 'fa-star' }}"></i> {{ tr($kb['title'] ?? '') }} — {{ tr($kb['description'] ?? '') }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.sections.agenda') }}
                        </th>
                        <td>
                            @if($landingPage->agenda)
                                <ul class="mb-0">
                                    @foreach($landingPage->agenda as $ag)
                                        <li>{{ $ag['time'] ?? '' }} — {{ tr($ag['title'] ?? '') }} ({{ tr($ag['speaker'] ?? '') }})</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.speaker_name') }}
                        </th>
                        <td>
                            @if($landingPage->speaker_avatar)
                                <img src="{{ $landingPage->speaker_avatar->getUrl('thumb') }}" class="img-thumbnail mr-2" style="max-height:40px;">
                            @endif
                            {{ $landingPage->speaker_name }} — {{ $landingPage->speaker_role }} @ {{ $landingPage->speaker_company }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.calendar_enabled') }}
                        </th>
                        <td>
                            {{ $landingPage->calendar_enabled ? trans('global.yes') : trans('global.no') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.zalo_url') }}
                        </th>
                        <td>
                            {{ $landingPage->zalo_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.fanpage_url') }}
                        </th>
                        <td>
                            {{ $landingPage->fanpage_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.is_published') }}
                        </th>
                        <td>
                            {{ $landingPage->is_published ? trans('global.yes') : trans('global.no') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.landingPage.fields.leads') }}
                        </th>
                        <td>
                            {{ $landingPage->leads()->count() }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div class="mt-4">
            <h5 class="mb-3"><strong>Đăng ký nhận tài liệu</strong></h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>CRM Tag</th>
                            <th>Document Link</th>
                            <th>Source (Chia sẻ)</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($landingPage->leads()->orderByDesc('created_at')->get() as $lead)
                            <tr>
                                <td>{{ $lead->id }}</td>
                                <td>{{ $lead->name }}</td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->crm_tag }}</td>
                                <td>
                                    @if($lead->document_url)
                                        <a href="{{ $lead->document_url }}" target="_blank" rel="noopener">Tải tài liệu</a>
                                    @endif
                                </td>
                                <td>
                                    @if($lead->source_url)
                                        <a href="{{ $lead->source_url }}" target="_blank" rel="noopener">Chia sẻ</a>
                                    @endif
                                </td>
                                <td>{{ $lead->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Chưa có đăng ký nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>
@endsection
