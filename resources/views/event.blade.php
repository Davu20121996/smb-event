@extends('layouts.main')

@if(isset($event) && ($event->pc_bg_image_url || $event->mobile_bg_image_url))
  @section('body_class', 'event-page-body')
@endif

@section('seo_title', trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['<br>', '<br/>', '<br />', '</span>', '</p>', '</h1>'], ' ', tr($settings['title'] ?? ''))))) ?: ($event->meta_title ?: $event->name ?: __('frontend.nav_event')))
@section('seo_description', $event->meta_description ?: __('frontend.seo_event_desc'))
@section('seo_image', $event->og_image ?: asset('img/smbplus/logo-1.png'))
@if($event->favicon_url)
    @section('seo_favicon', $event->favicon_url)
@endif

@section('content')
@if(isset($event) && ($event->pc_bg_image_url || $event->mobile_bg_image_url))
  <div class="event-page-bg" aria-hidden="true">
    @if($event->mobile_bg_image_url)
      <picture class="event-bg-picture">
        <source media="(max-width: 767px)" srcset="{{ $event->mobile_bg_image_url }}">
        <img src="{{ $event->pc_bg_image_url ?: $event->mobile_bg_image_url }}" alt="" class="event-bg-img">
      </picture>
    @else
      <img src="{{ $event->pc_bg_image_url }}" alt="" class="event-bg-img">
    @endif
    <div class="event-bg-overlay"></div>
  </div>
@endif

@include('sections.intro')

<main id="main">
  @include('sections.key_benefits')

  @include('sections.speakers')

  @include('sections.schedule')

  @include('sections.venues')

  @if($event->show_gallery)
    @include('sections.gallery')
  @endif

  @if($event->show_sponsors)
    @include('sections.sponsors')
  @endif

  @include('sections.faq')

  @if($event->show_tickets)
    @include('sections.buy_ticket')
  @endif

  @include('sections.event_register')
</main>
@endsection

@section('scripts')
<script>
  (function () {
    document.documentElement.style.scrollBehavior = 'smooth';
    window.addEventListener('scroll', function () {}, { passive: true });

    var style = document.createElement('style');
    style.textContent = [
      '#main section, #main .container, #main .row { scroll-margin-top: 110px; }',
      'body.event-page-body #main section[id] { scroll-margin-top: 100px; }',
      'body.event-page-body .wow { animation-duration: 1s; }'
    ].join('\n');
    document.head.appendChild(style);
  })();
</script>
@if(isset($event) && $event->countdown_enabled && $event->registration_deadline)
<script>
  (function () {
    var deadline = document.getElementById('eventCountdown');
    if (!deadline) return;
    var target = new Date(deadline.getAttribute('data-deadline')).getTime();

    function tick() {
      var now = new Date().getTime();
      var diff = target - now;
      if (diff < 0) diff = 0;
      var days = Math.floor(diff / (1000 * 60 * 60 * 24));
      var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((diff % (1000 * 60)) / 1000);
      var pad = function (n) { return n < 10 ? '0' + n : n; };
      deadline.querySelector('[data-unit="days"]').textContent = pad(days);
      deadline.querySelector('[data-unit="hours"]').textContent = pad(hours);
      deadline.querySelector('[data-unit="minutes"]').textContent = pad(minutes);
      deadline.querySelector('[data-unit="seconds"]').textContent = pad(seconds);
    }
    tick();
    setInterval(tick, 1000);
  })();
</script>
@endif

<style>
  .required-star { color: #e53935; margin-left: 2px; font-weight: bold; }
  .field-error    { border-color: #e53935 !important; }
  .validation.error-msg { color: #e53935; font-size: 12px; margin-top: 4px; display: block; }
</style>
<script>
  (function () {
    var form = document.querySelector('.eventRegisterForm');
    if (!form) return;
    var thankYouUrl = "{{ route('event.thank-you') }}";

    var messages = {
      required : "{{ __('frontend.event_register_required') }}",
      email    : "{{ __('frontend.event_register_email_invalid') }}",
      phone    : "{{ __('frontend.event_register_phone_invalid') }}"
    };

    function showError(input, msg) {
      input.classList.add('field-error');
      var err = input.parentNode.querySelector('.validation');
      if (err) { err.textContent = msg; err.classList.add('error-msg'); }
    }

    function clearError(input) {
      input.classList.remove('field-error');
      var err = input.parentNode.querySelector('.validation');
      if (err) { err.textContent = ''; err.classList.remove('error-msg'); }
    }

    // Live-clear on input
    form.querySelectorAll('input, select').forEach(function (el) {
      el.addEventListener('input', function () { clearError(el); });
      el.addEventListener('change', function () { clearError(el); });
    });

    function validateForm() {
      var ok = true;

      // Họ và tên
      var name = form.querySelector('#er_name');
      if (!name.value.trim()) { showError(name, messages.required); ok = false; }
      else clearError(name);

      // SĐT
      var phone = form.querySelector('#er_phone');
      if (!phone.value.trim()) {
        showError(phone, messages.required); ok = false;
      } else if (!/^[0-9+\-\s()]{7,20}$/.test(phone.value.trim())) {
        showError(phone, messages.phone); ok = false;
      } else clearError(phone);

      // Email
      var email = form.querySelector('#er_email');
      if (!email.value.trim()) {
        showError(email, messages.required); ok = false;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        showError(email, messages.email); ok = false;
      } else clearError(email);

      // Công ty
      var company = form.querySelector('#er_company');
      if (!company.value.trim()) { showError(company, messages.required); ok = false; }
      else clearError(company);

      return ok;
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!validateForm()) return;

      var btn = form.querySelector('button[type="submit"]');
      var originalText = btn.innerHTML;
      btn.innerHTML = 'Sending...';
      btn.disabled = true;

      var xhr = new XMLHttpRequest();
      xhr.open('POST', form.getAttribute('action'), true);
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.setRequestHeader('X-CSRF-TOKEN', form.querySelector('input[name="_token"]').value);

      xhr.send(new FormData(form));

      xhr.onload = function () {
        if (xhr.status === 200) {
          window.location.href = thankYouUrl;
        } else {
          btn.innerHTML = originalText;
          btn.disabled = false;
        }
      };

      xhr.onerror = function () {
        btn.innerHTML = originalText;
        btn.disabled = false;
      };
    });
  })();
</script>
@endsection
