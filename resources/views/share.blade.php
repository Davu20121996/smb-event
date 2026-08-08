@extends('layouts.main')

@section('seo_title', __('frontend.seo_share_title'))
@section('seo_description', __('frontend.seo_share_desc'))

@section('content')
<main id="main" class="main-page">
  <section id="share" class="wow fadeIn section-tight">
    <div class="container">
      <div class="section-header text-center">
        <p class="section-eyebrow">SMB+ Documents</p>
        <h2>{{ __('frontend.share_title') }}</h2>
        <p>{{ __('frontend.share_sub') }}</p>
      </div>

      @if($landingPages->count())
        <div class="row">
          @foreach($landingPages as $landingPage)
            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp">
              <article class="project-card h-100">
                <a href="{{ route('share.show', $landingPage->slug) }}" class="d-block">
                  @if($landingPage->thumbnail)
                    <div class="project-card-img">
                      <img src="{{ $landingPage->thumbnail->getUrl('card') }}" alt="{{ $landingPage->title }}">
                    </div>
                  @endif
                  <div class="project-card-body">
                    <h3>{{ tr($landingPage->title) }}</h3>
                    @if($landingPage->form_title)
                      <p>{{ tr($landingPage->form_title) }}</p>
                    @endif
                  </div>
                </a>
              </article>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center">
          <p class="body-md" style="color: var(--ink-muted);">{{ __('frontend.share_no_docs') }}</p>
        </div>
      @endif
    </div>
  </section>
</main>
@endsection
