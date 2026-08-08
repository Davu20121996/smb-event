@extends('layouts.main')

@section('seo_title', __('frontend.seo_projects_title'))
@section('seo_description', __('frontend.seo_projects_desc'))

@section('content')
<main id="main" class="main-page">
  <section id="projects" class="wow fadeIn section-tight">
    <div class="container">
      <div class="section-header text-center">
        <p class="section-eyebrow">{{ tr($settings['sec_projects_eyebrow'] ?? '') ?: __('frontend.projects_title') }}</p>
        <h2>{{ tr($settings['sec_projects_title'] ?? '') ?: __('frontend.projects_title') }}</h2>
        <p>{{ tr($settings['sec_projects_subtitle'] ?? '') ?: __('frontend.seo_projects_desc') }}</p>
      </div>

      @if($logos->count())
        <div class="row align-items-center">
          @foreach($logos as $logo)
            <div class="col-lg-3 col-md-4 col-6 mb-4 wow fadeInUp">
              <div class="case-study-logo">
                <img src="{{ $logo }}" alt="Khách hàng SMB+" loading="lazy">
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center">
          <p class="body-md" style="color: var(--ink-muted);">{{ __('frontend.projects_no') }}</p>
        </div>
      @endif
    </div>
  </section>
</main>
@endsection
