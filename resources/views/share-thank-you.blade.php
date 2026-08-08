@extends('layouts.main')

@section('seo_title', __('frontend.seo_thanks_title'))
@section('seo_description', __('frontend.seo_thanks_desc'))
@section('seo_favicon', asset('img/smbplus/cropped-cropped-SMB-icon-180x180-1-32x32.png'))

@section('content')
<main id="main" class="main-page">
  <section id="share-thank-you" class="wow fadeIn section-tight">
    <div class="container">
      <div class="text-center" style="max-width: 640px; margin: 0 auto;">
        <div class="share-thank-icon">
          <i class="fa fa-check-circle-o" aria-hidden="true"></i>
        </div>
        <h2>{{ __('frontend.thanks_interest') }}</h2>
        <p class="body-md mt-3" style="color: var(--ink-muted);">
          {{ $landingPage->download_title ?: __('frontend.thanks_sub') }}
        </p>

        @if($landingPage->pdf_enabled)
          <div class="mt-4">
            @if($landingPage->pdf_source == 'url' && $landingPage->pdf_url)
              <a href="{{ $landingPage->pdf_url }}" target="_blank" class="btn-software-primary" rel="noopener">
                <i class="fa fa-download" aria-hidden="true"></i> {{ $landingPage->download_button_title ?: __('frontend.download_pdf') }}
              </a>
            @elseif($landingPage->pdf_file)
              <a href="{{ $landingPage->pdf_file->getUrl() }}" target="_blank" class="btn-software-primary" rel="noopener">
                <i class="fa fa-download" aria-hidden="true"></i> {{ $landingPage->download_button_title ?: __('frontend.download_pdf') }}
              </a>
            @endif
          </div>
        @endif

        @if($landingPage->report_url)
          <div class="mt-3">
            <a href="{{ $landingPage->report_url }}" target="_blank" class="text-link" rel="noopener">
              {{ __('frontend.view_report_detail') }}
            </a>
          </div>
        @endif

        @if($landingPage->calendar_enabled && $landingPage->registration_deadline)
          @php
            $calTitle = $landingPage->title;
            $calStart = preg_replace('/[^0-9]/', '', $landingPage->registration_deadline->toDateTimeString());
            $calEnd   = $landingPage->registration_deadline->copy()->addHour()->format('YmdHis');
            $googleCal = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . urlencode($calTitle) . '&dates=' . $calStart . '/' . $calEnd . '&details=' . urlencode(strip_tags($landingPage->content ?? ''));
            $outlookCal = 'https://outlook.live.com/calendar/0/action/compose?subject=' . urlencode($calTitle) . '&startdt=' . urlencode($landingPage->registration_deadline->format('Y-m-d\TH:i:s')) . '&enddt=' . urlencode($landingPage->registration_deadline->copy()->addHour()->format('Y-m-d\TH:i:s')) . '&body=' . urlencode(strip_tags($landingPage->content ?? ''));
          @endphp
          <div class="mt-4">
            <p class="body-sm mb-2" style="color: var(--ink-muted);">{{ __('frontend.remind_doc_calendar') }}</p>
            <div class="event-thank-actions">
              <a href="{{ $googleCal }}" target="_blank" rel="noopener" class="btn-software-primary">
                <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Google Calendar
              </a>
              <a href="{{ $outlookCal }}" target="_blank" rel="noopener" class="btn-software-primary btn-soft">
                <i class="fa fa-calendar-o" aria-hidden="true"></i> Outlook Calendar
              </a>
            </div>
          </div>
        @endif

        @if($landingPage->zalo_url || $landingPage->fanpage_url)
          <div class="mt-4">
            <p class="body-sm mb-2" style="color: var(--ink-muted);">{{ __('frontend.join_community') }}</p>
            <div class="event-thank-actions">
              @if($landingPage->zalo_url)
                <a href="{{ $landingPage->zalo_url }}" target="_blank" rel="noopener" class="btn-software-primary btn-soft">
                  <i class="fa fa-comments" aria-hidden="true"></i> {{ __('frontend.zalo_community') }}
                </a>
              @endif
              @if($landingPage->fanpage_url)
                <a href="{{ $landingPage->fanpage_url }}" target="_blank" rel="noopener" class="btn-software-primary btn-soft">
                  <i class="fa fa-facebook-square" aria-hidden="true"></i> Fanpage
                </a>
              @endif
            </div>
          </div>
        @endif

        <div class="mt-5">
          <a href="{{ route('share.index') }}" class="text-link">{!! __('frontend.back_to_docs') !!}</a>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
