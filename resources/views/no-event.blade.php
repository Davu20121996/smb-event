@extends('layouts.main')

@section('seo_title', __('frontend.nav_event') . ' | ' . config('app.name', 'SMB+'))
@section('seo_description', __('frontend.seo_event_desc'))

@section('content')
<main id="main" class="main-page">
  <section id="no-event" class="wow fadeIn section-tight" style="padding: 140px 0; background-color: #f5f7fa;">
    <div class="container">
      <div class="text-center" style="max-width: 640px; margin: 0 auto; padding: 40px 20px; background: #fff; border-radius: 16px; border: 1px solid #e8ecf1; box-shadow: 0 10px 30px rgba(16,24,40,.06);">
        <div style="font-size: 72px; color: var(--primary); margin-bottom: 24px; animation: pulse 2s infinite;">
          <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
        </div>
        <h2 style="font-weight: 800; color: #1a2332; font-size: 28px; margin-bottom: 16px;">
          {{ __('frontend.no_active_event_title') }}
        </h2>
        <p class="body-md" style="color: #5a6a7a; font-size: 15px; line-height: 1.7; margin-bottom: 32px;">
          {{ __('frontend.no_active_event_desc') }}
        </p>
        <div>
          <a href="{{ route('home') }}" class="nav-contact-btn" style="padding: 10px 24px; font-size: 14px; font-weight: 600;">
            <i class="fa fa-home" aria-hidden="true" style="margin-right: 6px;"></i> {{ __('frontend.nav_home') }}
          </a>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
