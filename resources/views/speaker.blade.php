@extends('layouts.main')

@section('seo_title', tr($speaker->name) . ' — SMB+')
@section('seo_description', tr($speaker->full_description ?? ''))

@section('content')
<main id="main" class="main-page">
  <section id="speakers-details" class="wow fadeIn section-tight">
    <div class="container">
      <div class="section-header">
        <h2>{{ __('frontend.speaker_details') }}</h2>
        <p>Praesentium ut qui possimus sapiente nulla.</p>
      </div>

      <div class="feature-card-elevated">
        <div class="row align-items-center">
          <div class="col-md-5">
            <img src="{{ $speaker->photo->getUrl() }}" alt="{{ tr($speaker->name) }}" class="img-fluid" style="border-radius: var(--radius-lg); border: 1px solid var(--hairline);">
          </div>

          <div class="col-md-7">
            <div class="details">
              <h2 class="heading-2">{{ tr($speaker->name) }}</h2>
              @if($speaker->role)
                <p class="body-md mt-1" style="color: var(--primary); font-weight: 600;">{{ tr($speaker->role) }}</p>
              @endif
              @if($speaker->company)
                <p class="body-sm" style="color: var(--ink-muted);">{{ tr($speaker->company) }}</p>
              @endif
              <div class="social mt-3 mb-3" style="display:flex; gap:8px;">
                @if($speaker->twitter)
                  <a href="{{ $speaker->twitter }}" aria-label="Twitter" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:9999px; border:1px solid var(--hairline); color:var(--ink-muted);"><i class="fa fa-twitter"></i></a>
                @endif
                @if($speaker->facebook)
                  <a href="{{ $speaker->facebook }}" aria-label="Facebook" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:9999px; border:1px solid var(--hairline); color:var(--ink-muted);"><i class="fa fa-facebook"></i></a>
                @endif
                @if($speaker->linkedin)
                  <a href="{{ $speaker->linkedin }}" aria-label="LinkedIn" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:9999px; border:1px solid var(--hairline); color:var(--ink-muted);"><i class="fa fa-linkedin"></i></a>
                @endif
              </div>
              <p class="body-md" style="color: var(--ink-muted);">{!! tr($speaker->full_description) !!}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
