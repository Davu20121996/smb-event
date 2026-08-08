<footer id="footer">

  <div class="footer-main">
    <div class="container">
      <div class="row">

        {{-- Cột 1: Thương hiệu + Liên hệ + Follow Us --}}
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="footer-brand">
            <a href="{{ route('home') }}" class="footer-logo-link">
              <img src="{{ asset('img/smbplus/logo-1.png') }}" alt="{{ config('app.name', 'SMB+') }}" class="footer-logo">
            </a>
            <ul class="footer-contact-list">
              @if(($settings['footer_address'] ?? '') || ($settings['contact_address'] ?? ''))
                <li>
                  <i class="fa fa-map-marker" aria-hidden="true"></i>
                  <span>{!! tr($settings['footer_address'] ?? '') ?: tr($settings['contact_address'] ?? '') !!}</span>
                </li>
              @endif
              @if($settings['contact_phone'] ?? '')
                <li>
                  <i class="fa fa-phone" aria-hidden="true"></i>
                  <a href="tel:{{ str_replace(' ', '', $settings['contact_phone']) }}">{{ $settings['contact_phone'] }}</a>
                </li>
              @endif
              @if($settings['contact_email'] ?? '')
                <li>
                  <i class="fa fa-envelope-o" aria-hidden="true"></i>
                  <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a>
                </li>
              @endif
            </ul>
            <h4 class="footer-heading">{{ __('frontend.footer_follow_us') }}</h4>
            <div class="footer-social">
              @if($settings['footer_facebook'] ?? '')
                <a href="{{ $settings['footer_facebook'] }}" class="footer-social-link" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
              @endif
              @if($settings['footer_linkedin'] ?? '')
                <a href="{{ $settings['footer_linkedin'] }}" class="footer-social-link" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
              @endif
              @if($settings['footer_instagram'] ?? '')
                <a href="{{ $settings['footer_instagram'] }}" class="footer-social-link" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
              @endif
              @if($settings['footer_twitter'] ?? '')
                <a href="{{ $settings['footer_twitter'] }}" class="footer-social-link" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
              @endif
              @if(!($settings['footer_facebook'] ?? '') && !($settings['footer_linkedin'] ?? '') && !($settings['footer_instagram'] ?? '') && !($settings['footer_twitter'] ?? ''))
                <a href="#" class="footer-social-link" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                <a href="#" class="footer-social-link" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
                <a href="#" class="footer-social-link" aria-label="YouTube"><i class="fa fa-youtube-play"></i></a>
              @endif
            </div>
          </div>
        </div>

        {{-- Cột 2: Dịch vụ --}}
        <div class="col-lg-3 col-md-6 mb-4">
          <h4 class="footer-heading">{{ __('frontend.footer_services') }}</h4>
          <ul class="footer-links">
            <li><a href="{{ route('home') }}#services">Software Engineering</a></li>
            <li><a href="{{ route('home') }}#services">Technology Consulting</a></li>
            <li><a href="{{ route('home') }}#services">Testing service</a></li>
          </ul>
        </div>

        {{-- Cột 3: Giải pháp --}}
        <div class="col-lg-3 col-md-6 mb-4">
          <h4 class="footer-heading">{{ __('frontend.footer_solutions') }}</h4>
          <ul class="footer-links">
            <li><a href="{{ route('home') }}#software">HRM+</a></li>
            <li><a href="{{ route('home') }}#software">Freshworks</a></li>
            <li><a href="{{ route('home') }}#projects">Case Studies</a></li>
          </ul>
        </div>

        {{-- Cột 4: Khám phá --}}
        <div class="col-lg-2 col-md-6 mb-4">
          <h4 class="footer-heading">{{ __('frontend.footer_explore') }}</h4>
          <ul class="footer-links">
            <li><a href="{{ route('posts.index') }}">{{ __('frontend.footer_news') }}</a></li>
            <li><a href="{{ route('home') }}#contact">{{ __('frontend.footer_contact') }}</a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container text-center">
      <p class="mb-0">Copyright &copy; 2026 <strong>SMB+</strong></p>
    </div>
  </div>

</footer>
