@php
    $routeName = Route::currentRouteName();
    $isEventPage = in_array($routeName, ['event', 'event.show', 'speaker']);
    $base = $isEventPage ? route('event') : route('home');
@endphp

{{-- Top bar info --}}
<div id="topbar">
  <div class="container d-flex align-items-center justify-content-between">
    <div class="topbar-left d-flex align-items-center gap-3">
      @if($settings['contact_phone'] ?? '')
        <span class="topbar-item">
          <i class="fa fa-phone" aria-hidden="true"></i>
          <a href="tel:{{ str_replace(' ', '', $settings['contact_phone']) }}">{{ $settings['contact_phone'] }}</a>
        </span>
      @endif
      @if($settings['contact_email'] ?? '')
        <span class="topbar-item">
          <i class="fa fa-envelope-o" aria-hidden="true"></i>
          <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a>
        </span>
      @endif
    </div>
    <div class="topbar-right d-flex align-items-center gap-2">
      @if($settings['footer_facebook'] ?? '')
        <a href="{{ $settings['footer_facebook'] }}" class="topbar-social" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
      @endif
      @if($settings['footer_twitter'] ?? '')
        <a href="{{ $settings['footer_twitter'] }}" class="topbar-social" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
      @endif
      @if($settings['footer_linkedin'] ?? '')
        <a href="{{ $settings['footer_linkedin'] }}" class="topbar-social" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
      @endif
    </div>
  </div>
</div>

<header id="header" class="nav-bar">
  <div class="container d-flex align-items-center justify-content-between">

    <div id="logo" class="pull-left">
      <a href="{{ route('home') }}" class="logo-link">
        <img src="{{ asset('img/smbplus/logo-1.png') }}" alt="{{ config('app.name', 'SMB+') }}" class="logo-img">
      </a>
    </div>

    <nav id="nav-menu-container">
      <ul class="nav-menu">
        @if($isEventPage)
          <li class="menu-active"><a href="{{ $base }}#intro">{{ __('frontend.nav_home') }}</a></li>
          <li><a href="{{ $base }}#speakers">{{ __('frontend.nav_speakers') }}</a></li>
          <li><a href="{{ $base }}#schedule">{{ __('frontend.nav_schedule') }}</a></li>
          <li><a href="{{ $base }}#venue">{{ __('frontend.nav_venue') }}</a></li>
          <li><a href="{{ $base }}#contact">{{ __('frontend.nav_contact') }}</a></li>
        @else
          @forelse($navMenus as $menu)
            @if($menu->children->count())
              <li class="menu-has-children">
                <a href="{{ $menu->url ?: '#' }}">{{ tr($menu->label) }}</a>
                <ul class="nav-menu-sub">
                  @foreach($menu->children as $child)
                    <li>
                      <a href="{{ $child->url ?: '#' }}">{{ tr($child->label) }}</a>
                    </li>
                  @endforeach
                </ul>
              </li>
            @else
              <li>
                <a href="{{ $menu->url ?: '#' }}">{{ tr($menu->label) }}</a>
              </li>
            @endif
          @empty
            <li class="menu-active"><a href="{{ $base }}#intro">{{ __('frontend.nav_home') }}</a></li>
            <li><a href="{{ $base }}#about">{{ __('frontend.nav_about') }}</a></li>
            <li><a href="{{ $base }}#services">{{ __('frontend.nav_services') }}</a></li>
            <li><a href="{{ route('event') }}">{{ __('frontend.nav_event') }}</a></li>
            <li><a href="{{ $base }}#projects">{{ __('frontend.nav_projects') }}</a></li>
            <li><a href="{{ route('posts.index') }}">{{ __('frontend.nav_news') }}</a></li>
            <li><a href="{{ $base }}#contact">{{ __('frontend.nav_contact') }}</a></li>
          @endforelse
        @endif
      </ul>
    </nav>

    <div class="nav-actions d-flex align-items-center gap-2">
      <div class="nav-lang-switcher" id="navLangSwitcher">
        <button type="button" class="nav-lang-current" aria-haspopup="true" aria-expanded="false" aria-label="{{ __('frontend.language') }}">
          <span class="nav-lang-globe" aria-hidden="true"><i class="fa fa-globe"></i></span>
          <span class="nav-lang-code">{{ strtoupper(app()->getLocale()) }}</span>
          <span class="nav-lang-caret" aria-hidden="true"><i class="fa fa-caret-down"></i></span>
        </button>
        <ul class="nav-lang-dropdown" role="menu">
          @foreach(config('panel.available_languages', ['vi' => 'Tiếng Việt', 'en' => 'English']) as $langLocale => $langName)
            <li role="none">
              <form method="POST" action="{{ route('locale.switch') }}" class="nav-lang-form">
                @csrf
                <input type="hidden" name="locale" value="{{ $langLocale }}">
                <button type="submit" role="menuitem" class="nav-lang-item {{ app()->getLocale() === $langLocale ? 'active' : '' }}">
                  <span class="nav-lang-name">{{ $langName }}</span>
                  @if(app()->getLocale() === $langLocale)
                    <span class="nav-lang-check" aria-hidden="true"><i class="fa fa-check"></i></span>
                  @endif
                </button>
              </form>
            </li>
          @endforeach
        </ul>
      </div>
      @if($isEventPage)
        <a class="nav-contact-btn nav-register-btn" href="{{ $base }}#buy-tickets">{{ __('frontend.register_now') }}</a>
      @else
        <a class="nav-contact-btn" href="#contact">{{ __('frontend.nav_contact_now') }}</a>
      @endif
    </div>

  </div>
</header>
