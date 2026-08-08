<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  @php
    $seoTitle = View::hasSection('seo_title')
        ? View::yieldContent('seo_title') . ' | ' . config('app.name', 'SMB+')
        : config('app.name', 'SMB+') . ' — ' . __('frontend.seo_default_title');
    $seoDesc = View::hasSection('seo_description')
        ? View::yieldContent('seo_description')
        : __('frontend.seo_default_desc');
    $seoImg = View::hasSection('seo_image')
        ? View::yieldContent('seo_image')
        : asset('img/smbplus/logo-1.png');
    $dsVer  = file_exists(public_path('css/design-system.css')) ? filemtime(public_path('css/design-system.css')) : 1;
    $cssVer = file_exists(public_path('css/theme.css'))         ? filemtime(public_path('css/theme.css'))         : 1;
    $customVer = file_exists(public_path('css/custom.css'))     ? filemtime(public_path('css/custom.css'))     : 1;
    $jsVer  = file_exists(public_path('js/contactform.js'))     ? filemtime(public_path('js/contactform.js'))     : 1;
    $jsAppVer = file_exists(public_path('js/app.js'))           ? filemtime(public_path('js/app.js'))             : 1;
  @endphp

  <title>{{ $seoTitle }}</title>
  <meta name="description" content="{{ $seoDesc }}">
  <meta name="robots"      content="index, follow">
  <meta name="author"      content="{{ config('app.name', 'SMB+') }}">
  <link rel="canonical"    href="{{ url()->current() }}">

  <meta property="og:type"        content="website">
  <meta property="og:url"         content="{{ url()->current() }}">
  <meta property="og:title"       content="{{ $seoTitle }}">
  <meta property="og:description" content="{{ $seoDesc }}">
  <meta property="og:image"       content="{{ $seoImg }}">
  <meta property="og:locale"      content="{{ str_replace('-', '_', app()->getLocale()) }}">

  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="{{ $seoTitle }}">
  <meta name="twitter:description" content="{{ $seoDesc }}">
  <meta name="twitter:image"       content="{{ $seoImg }}">

  @if(View::hasSection('seo_favicon'))
    <link rel="icon" type="image/x-icon" href="{{ View::yieldContent('seo_favicon') }}">
  @else
    <link rel="icon" type="image/png" sizes="32x32"   href="{{ asset('img/smbplus/smb-logo-favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/smbplus/smb-logo-favicon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"      href="{{ asset('img/smbplus/smb-logo-favicon-180.png') }}">
  @endif
  <meta name="theme-color" content="#1a2332">

  @php
    $brandLogo = asset('img/smbplus/logo-1.png');
    $brandFavicon = asset('img/smbplus/cropped-cropped-SMB-icon-180x180-1-180x180.png');
    $orgSchema = [
      '@context' => 'https://schema.org',
      '@type'    => 'Organization',
      'name'     => config('app.name', 'SMB+'),
      'url'      => config('app.url'),
      'logo'     => [
        '@type'  => 'ImageObject',
        'url'    => $brandLogo,
        'width'  => 512,
        'height' => 512,
      ],
      'image'    => $brandFavicon,
    ];
    $websiteSchema = [
      '@context'   => 'https://schema.org',
      '@type'      => 'WebSite',
      'name'       => config('app.name', 'SMB+'),
      'url'        => config('app.url'),
      'potentialAction' => [
        '@type'       => 'SearchAction',
        'target'      => config('app.url') . '/?s={search_term_string}',
        'query-input' => 'required name=search_term_string',
      ],
    ];
  @endphp
  <script type="application/ld+json">{!! json_encode($orgSchema) !!}</script>
  <script type="application/ld+json">{!! json_encode($websiteSchema) !!}</script>

  <!-- Google Fonts: Be Vietnam Pro + Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('lib/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
  <link href="{{ asset('lib/venobox/venobox.css') }}" rel="stylesheet">
  <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

  <link href="{{ asset('css/design-system.css') }}?v={{ $dsVer }}" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('css/theme.css') }}?v={{ $cssVer }}" rel="stylesheet">
  <link href="{{ asset('css/custom.css') }}?v={{ $customVer }}" rel="stylesheet">
</head>
<body class="@yield('body_class')">

@include('partials.header')

@yield('content')

@include('partials.footer')

@if(Route::is('event.show'))
  <a href="#buy-tickets" class="frap-btn scrollto" aria-label="Get tickets">
    <i class="fa fa-ticket" aria-hidden="true"></i>
  </a>
@endif

<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>
<div class="toast-stack" id="toastStack"></div>

<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/jquery/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('lib/superfish/hoverIntent.js') }}"></script>
<script src="{{ asset('lib/superfish/superfish.min.js') }}"></script>
<script src="{{ asset('lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('lib/venobox/venobox.min.js') }}"></script>
<script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/contactform.js') }}?v={{ $jsVer }}"></script>
<script src="{{ asset('js/app.js') }}?v={{ $jsAppVer }}"></script>

@yield('scripts')

</body>
</html>
