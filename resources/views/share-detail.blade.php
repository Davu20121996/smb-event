@extends('layouts.main')

@section('seo_title', tr($landingPage->title) . ' — SMB+')
@section('seo_description', tr($landingPage->form_title ?? '') ?: tr($landingPage->title))
@section('seo_image', ($landingPage->thumbnail ? $landingPage->thumbnail->getUrl() : ($landingPage->cover ? $landingPage->cover->getUrl() : asset('img/smbplus/logo-1.png'))))
@section('seo_favicon', asset('img/smbplus/cropped-cropped-SMB-icon-180x180-1-32x32.png'))

@section('content')
<main id="main" class="main-page">

  {{-- ============ HERO: key visual + AI animation ============ --}}
  <section id="landing-hero" class="landing-hero" aria-label="{{ $landingPage->title }}">
    <div class="landing-hero-bg" aria-hidden="true">
      <div class="landing-hero-grid" aria-hidden="true"></div>
      <div class="landing-orb landing-orb-1" aria-hidden="true"></div>
      <div class="landing-orb landing-orb-2" aria-hidden="true"></div>
      <div class="landing-orb landing-orb-3" aria-hidden="true"></div>
      <div class="landing-particles" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span>
      </div>
    </div>

    <div class="container landing-hero-inner">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <nav class="share-breadcrumb landing-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">{{ __('frontend.post_home') }}</a>
            <span class="sep">/</span>
            <a href="{{ route('share.index') }}">{{ __('frontend.share_documents') }}</a>
            <span class="sep">/</span>
            <span class="current">{{ tr($landingPage->title) }}</span>
          </nav>

          <p class="landing-eyebrow">
            <span class="landing-eyebrow-dot" aria-hidden="true"></span>
            SMB+ Documents
          </p>

          <h1 class="landing-hero-title">{{ tr($landingPage->title) }}</h1>

          @if($landingPage->form_title)
            <p class="landing-hero-sub">{{ tr($landingPage->form_title) }}</p>
          @endif

          @if($landingPage->countdown_enabled && $landingPage->registration_deadline)
            <div class="landing-countdown-wrap">
              <p class="landing-countdown-label"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ __('frontend.registration_ends_in') }}</p>
              <div class="event-countdown" id="landingCountdown" data-deadline="{{ $landingPage->registration_deadline->toIso8601String() }}">
                <div class="countdown-box">
                  <span class="countdown-num" data-unit="days">00</span>
                  <span class="countdown-label">{{ __('frontend.countdown_days') }}</span>
                </div>
                <div class="countdown-box">
                  <span class="countdown-num" data-unit="hours">00</span>
                  <span class="countdown-label">{{ __('frontend.countdown_hours') }}</span>
                </div>
                <div class="countdown-box">
                  <span class="countdown-num" data-unit="minutes">00</span>
                  <span class="countdown-label">{{ __('frontend.countdown_minutes') }}</span>
                </div>
                <div class="countdown-box">
                  <span class="countdown-num" data-unit="seconds">00</span>
                  <span class="countdown-label">{{ __('frontend.countdown_seconds') }}</span>
                </div>
              </div>
              <p class="landing-countdown-expired" id="landingCountdownExpired" style="display:none;">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{ __('frontend.registration_closed') }}
              </p>
            </div>
          @endif

          <div class="landing-hero-cta">
            <a href="#share-register" class="btn-software-primary landing-hero-btn scrollto">
              <i class="fa fa-paper-plane" aria-hidden="true"></i> {{ tr($landingPage->button_title) ?: __('frontend.register_now') }}
            </a>
            @if($landingPage->report_url)
              <a href="{{ $landingPage->report_url }}" target="_blank" rel="noopener" class="btn-software-primary btn-soft landing-hero-btn">
                <i class="fa fa-line-chart" aria-hidden="true"></i> {{ __('frontend.view_report') }}
              </a>
            @endif
          </div>
        </div>

        <div class="col-lg-5">
          <div class="landing-key-visual">
            <div class="landing-kv-frame" aria-hidden="true"></div>
            <div class="landing-kv-card">
              @if($landingPage->cover)
                <img src="{{ $landingPage->cover->getUrl() }}" alt="{{ $landingPage->title }}" loading="eager">
              @elseif($landingPage->thumbnail)
                <img src="{{ $landingPage->thumbnail->getUrl() }}" alt="{{ $landingPage->title }}" loading="eager">
              @endif
            </div>
            <div class="landing-kv-chip landing-kv-chip-1">
              <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
              <span><strong>{{ __('frontend.pdf_doc') }}</strong><small>{{ __('frontend.pdf_free') }}</small></span>
            </div>
            <div class="landing-kv-chip landing-kv-chip-2">
              <i class="fa fa-rocket" aria-hidden="true"></i>
              <span><strong>{{ __('frontend.from_smb') }}</strong><small>{{ __('frontend.sme_solution') }}</small></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ============ KEY BENEFITS ============ --}}
  @if($landingPage->key_benefits)
    <section id="landing-benefits" class="wow fadeIn section-tight">
      <div class="container">
        <div class="section-header">
          <h2>{{ __('frontend.benefits_title') }}</h2>
          <p>{{ __('frontend.benefits_sub') }}</p>
        </div>
        <div class="row landing-benefits-grid">
          @foreach($landingPage->key_benefits as $kb)
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="landing-benefit">
                <div class="landing-benefit-icon">
                  <i class="fa {{ $kb['icon'] ?? 'fa-star' }}" aria-hidden="true"></i>
                </div>
                <h3>{{ tr($kb['title'] ?? '') }}</h3>
                <p>{{ tr($kb['description'] ?? '') }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- ============ AGENDA TIMELINE ============ --}}
  @if($landingPage->agenda)
    <section id="landing-agenda" class="wow fadeIn section-tight">
      <div class="container">
        <div class="section-header">
          <h2>{{ __('frontend.agenda_title') }}</h2>
          <p>{{ __('frontend.agenda_sub') }}</p>
        </div>
        <div class="landing-agenda-track">
          @foreach($landingPage->agenda as $index => $ag)
            <div class="landing-agenda-item">
              <div class="landing-agenda-marker">
                <span class="landing-agenda-dot"></span>
                <span class="landing-agenda-line" aria-hidden="true"></span>
              </div>
              <div class="landing-agenda-card">
                <span class="landing-agenda-time">{{ $ag['time'] ?? '' }}</span>
                <h3>{{ tr($ag['title'] ?? '') }}</h3>
                @if(!empty($ag['description']))
                  <p>{{ tr($ag['description']) }}</p>
                @endif
                @if(!empty($ag['speaker']))
                  <span class="landing-agenda-speaker"><i class="fa fa-user-circle-o" aria-hidden="true"></i> {{ tr($ag['speaker']) }}</span>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- ============ SPEAKER ============ --}}
  @if($landingPage->speaker_name)
    <section id="landing-speaker" class="wow fadeIn section-tight">
      <div class="container">
        <div class="landing-speaker-card">
          <div class="landing-speaker-avatar">
            @if($landingPage->speaker_avatar)
              <img src="{{ $landingPage->speaker_avatar->getUrl('card') }}" alt="{{ $landingPage->speaker_name }}" loading="lazy">
            @else
              <span class="landing-speaker-initial">{{ mb_substr($landingPage->speaker_name, 0, 1) }}</span>
            @endif
          </div>
          <div class="landing-speaker-info">
            <p class="landing-speaker-eyebrow">{{ __('frontend.speaker_share') }}</p>
            <h2>{{ tr($landingPage->speaker_name) }}</h2>
            <p class="landing-speaker-role">{{ tr($landingPage->speaker_role) }} @if($landingPage->speaker_company) · {{ $landingPage->speaker_company }} @endif</p>
            @if($landingPage->speaker_bio)
              <p class="landing-speaker-bio">{{ tr($landingPage->speaker_bio) }}</p>
            @endif
          </div>
        </div>
      </div>
    </section>
  @endif

  {{-- ============ CONTENT + FLOATING FORM ============ --}}
  <section id="share-register" class="wow fadeIn section-tight">
    <div class="container">
      <div class="row">
        <div class="col-lg-7">
          <div class="share-content landing-content">
            <h2 class="landing-content-title">{{ __('frontend.about_this_doc') }}</h2>
            {!! tr($landingPage->content) !!}
          </div>
        </div>

        <div class="col-lg-5">
          @if($errors->any())
            <div class="alert alert-danger mb-4">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="share-form-card landing-form-card">
            <div class="landing-form-head">
              <div class="landing-form-icon"><i class="fa fa-envelope-o" aria-hidden="true"></i></div>
              <h4>{{ tr($landingPage->form_title) ?: __('frontend.register_for_doc') }}</h4>
              <p>{{ __('frontend.form_hint') }}</p>
            </div>
            <form action="{{ route('share.register', $landingPage->slug) }}" method="POST" novalidate>
              @csrf
              <div class="form-group">
                <label for="name">{{ __('frontend.form_name_label') }}</label>
                <div class="landing-input">
                  <i class="fa fa-user-o" aria-hidden="true"></i>
                  <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ __('frontend.form_name_ph') }}" required>
                </div>
              </div>
              <div class="form-group">
                <label for="email">{{ __('frontend.form_email_label') }}</label>
                <div class="landing-input">
                  <i class="fa fa-envelope-o" aria-hidden="true"></i>
                  <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ __('frontend.form_email_ph') }}" required>
                </div>
              </div>
              <div class="form-group">
                <label for="phone">{{ __('frontend.form_phone_label') }}</label>
                <div class="landing-input">
                  <i class="fa fa-phone" aria-hidden="true"></i>
                  <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="{{ __('frontend.form_phone_ph') }}">
                </div>
              </div>
              <button type="submit" class="btn-software-primary landing-form-submit">
                <i class="fa fa-download" aria-hidden="true"></i> {{ tr($landingPage->button_title) ?: __('frontend.form_submit') }}
              </button>
              <p class="landing-form-note"><i class="fa fa-lock" aria-hidden="true"></i> {{ __('frontend.doc_info_secure') }}</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Back link --}}
  <section class="section-tight pt-0">
    <div class="container text-center">
      <a href="{{ route('share.index') }}" class="text-link">{!! __('frontend.back_to_docs') !!}</a>
    </div>
  </section>
</main>

{{-- ============ STICKY CTA ============ --}}
<div class="landing-sticky-cta" id="landingStickyCta">
  <a href="#share-register" class="btn-software-primary scrollto">
    <i class="fa fa-paper-plane" aria-hidden="true"></i> {{ __('frontend.register_now') }}
  </a>
</div>
@endsection

@section('scripts')
@parent
@if($landingPage->countdown_enabled && $landingPage->registration_deadline)
<script>
  (function () {
    var cd = document.getElementById('landingCountdown');
    if (!cd) return;
    var expiredMsg = document.getElementById('landingCountdownExpired');
    var ctaBtn = document.querySelector('#landing-hero .landing-hero-cta a.btn-software-primary');
    var target = new Date(cd.getAttribute('data-deadline')).getTime();

    function pad(n) { return n < 10 ? '0' + n : n; }

    function tick() {
      var diff = target - new Date().getTime();
      if (diff < 0) {
        cd.style.display = 'none';
        if (expiredMsg) expiredMsg.style.display = 'block';
        if (ctaBtn) ctaBtn.classList.add('disabled');
        return;
      }
      cd.querySelector('[data-unit="days"]').textContent = pad(Math.floor(diff / (1000 * 60 * 60 * 24)));
      cd.querySelector('[data-unit="hours"]').textContent = pad(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
      cd.querySelector('[data-unit="minutes"]').textContent = pad(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)));
      cd.querySelector('[data-unit="seconds"]').textContent = pad(Math.floor((diff % (1000 * 60)) / 1000));
    }
    tick();
    setInterval(tick, 1000);
  })();
</script>
@endif
<script>
  (function () {
    var sticky = document.getElementById('landingStickyCta');
    var hero = document.getElementById('landing-hero');
    if (!sticky || !hero) return;

    function onScroll() {
      var heroBottom = hero.offsetTop + hero.offsetHeight;
      if (window.scrollY > heroBottom) {
        sticky.classList.add('visible');
      } else {
        sticky.classList.remove('visible');
      }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  })();
</script>
@endsection
