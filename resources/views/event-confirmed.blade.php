@extends('layouts.main')

@section('seo_title', __('frontend.seo_event_confirmed_title', ['name' => $event->name ?? 'SMB+']))
@section('seo_description', __('frontend.seo_event_confirmed_desc'))

@section('content')
<main id="main" class="main-page">
  <section id="event-confirmed" class="wow fadeIn section-tight">
    <div class="container">
      <div class="text-center" style="max-width: 640px; margin: 0 auto;">
        <div class="share-thank-icon">
          <i class="fa fa-check-circle-o" aria-hidden="true"></i>
        </div>
        <h2>{{ __('frontend.event_confirmed') }}</h2>
        <p class="body-md mt-3" style="color: var(--ink-muted);">
          {{ __('frontend.event_confirmed_sub') }}
        </p>

        @if($event->zalo_url || $event->fanpage_url)
          <div class="mt-4">
            <p class="body-sm mb-2" style="color: var(--ink-muted);">{{ __('frontend.join_community') }}</p>
            <div class="event-thank-actions">
              @if($event->zalo_url)
                <a href="{{ $event->zalo_url }}" target="_blank" rel="noopener" class="btn-software-primary btn-soft">
                  <i class="fa fa-comments" aria-hidden="true"></i> {{ __('frontend.zalo_community') }}
                </a>
              @endif
              @if($event->fanpage_url)
                <a href="{{ $event->fanpage_url }}" target="_blank" rel="noopener" class="btn-software-primary btn-soft">
                  <i class="fa fa-facebook-square" aria-hidden="true"></i> Fanpage
                </a>
              @endif
            </div>
          </div>
        @endif

        <div class="mt-5">
          <a href="{{ route('event') }}" class="text-link">{!! __('frontend.back_to_event') !!}</a>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection