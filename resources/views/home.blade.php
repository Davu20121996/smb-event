@extends('layouts.main')

@section('seo_title', tr($settings['seo_title'] ?? '') ?: __('frontend.seo_home_title'))
@section('seo_description', tr($settings['seo_description'] ?? '') ?: __('frontend.seo_home_desc'))
@section('seo_keywords', tr($settings['seo_keywords'] ?? '') ?: __('frontend.seo_home_keywords'))
@section('seo_image', asset('img/smbplus/slider_d2.jpg'))

@section('content')

{{-- ============================================================
     HERO SECTION — ảnh full-width, text nằm trong ảnh, chiếm 50% height
     ============================================================ --}}
<section id="intro" class="home-hero hero-home" aria-label="Trang chủ SMB+">
  <div class="hero-inner">
    <div class="hero-bg">
      <img src="{{ asset('img/smbplus/slider_d2.jpg') }}"
           alt="SMB+ — Giải pháp phần mềm quản lý doanh nghiệp"
           class="hero-img"
           loading="eager">
      <div class="hero-overlay" aria-hidden="true"></div>

      <div class="hero-content wow fadeInLeft">
        <p class="hero-eyebrow">{{ __('frontend.hero_eyebrow') }}</p>
        <h1 class="hero-title">
          {!! tr($settings['company_title'] ?? '') ?: __('frontend.seo_default_title') !!}
        </h1>
        <p class="hero-sub">
          {{ tr($settings['company_subtitle'] ?? '') ?: __('frontend.software_headline') }}
        </p>
        <div class="hero-cta">
          <a href="#software" class="btn-hero-primary scrollto">{{ __('frontend.hero_explore') }}</a>
          @if($settings['company_youtube_link'] ?? '')
            <a href="{{ $settings['company_youtube_link'] }}" class="btn-hero-play venobox" data-vbtype="video" data-autoplay="true" aria-label="Xem video giới thiệu SMB+">
              <span class="play-circle"><i class="fa fa-play" aria-hidden="true"></i></span>
              <span class="play-label">{{ __('frontend.hero_watch_video') }}</span>
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
{{-- /HERO --}}

<main id="main">

  {{-- ============================================================
       SOFTWARE THEO YÊU CẦU — 2 cột
       ============================================================ --}}
  <section id="software" class="section-software wow fadeInUp">
    <div class="container">
      <div class="row align-items-center">

        {{-- Cột trái: mô tả --}}
        <div class="col-lg-5 col-md-12 mb-4 mb-lg-0">
          <p class="section-eyebrow">{{ tr($settings['sec_software_eyebrow'] ?? '') ?: __('frontend.software_title') }}</p>
          <h2 class="section-title-left">{!! tr($settings['sec_software_title'] ?? '') ?: __('frontend.software_headline') !!}</h2>
          <p class="section-desc">
            {{ tr($settings['company_about'] ?? '') ?: __('frontend.seo_default_desc') }}
          </p>
          <div class="software-cta-group">
            <a href="#contact" class="btn-software-primary scrollto">{{ __('frontend.contact_consult') }}</a>
            @if($settings['contact_phone'] ?? '')
              <a href="tel:{{ str_replace(' ', '', $settings['contact_phone']) }}" class="btn-software-phone">
                <i class="fa fa-phone-square" aria-hidden="true"></i>
                {{ $settings['contact_phone'] }}
              </a>
            @endif
          </div>
        </div>

        {{-- Cột phải: 4 bước đánh số --}}
        <div class="col-lg-7 col-md-12">
          <div class="steps-list">

            <div class="step-item wow fadeInRight" data-wow-delay="0.1s">
              <div class="step-number">1</div>
              <div class="step-content">
                <h4>{{ tr($settings['step1_title'] ?? '') ?: __('frontend.step1_title') }}</h4>
                <p>{{ tr($settings['step1_desc'] ?? '') ?: __('frontend.step1_desc') }}</p>
              </div>
            </div>

            <div class="step-item wow fadeInRight" data-wow-delay="0.2s">
              <div class="step-number">2</div>
              <div class="step-content">
                <h4>{{ tr($settings['step2_title'] ?? '') ?: __('frontend.step2_title') }}</h4>
                <p>{{ tr($settings['step2_desc'] ?? '') ?: __('frontend.step2_desc') }}</p>
              </div>
            </div>

            <div class="step-item wow fadeInRight" data-wow-delay="0.3s">
              <div class="step-number">3</div>
              <div class="step-content">
                <h4>{{ tr($settings['step3_title'] ?? '') ?: __('frontend.step3_title') }}</h4>
                <p>{{ tr($settings['step3_desc'] ?? '') ?: __('frontend.step3_desc') }}</p>
              </div>
            </div>

            <div class="step-item wow fadeInRight" data-wow-delay="0.4s">
              <div class="step-number">4</div>
              <div class="step-content">
                <h4>{{ tr($settings['step4_title'] ?? '') ?: __('frontend.step4_title') }}</h4>
                <p>{{ tr($settings['step4_desc'] ?? '') ?: __('frontend.step4_desc') }}</p>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
  {{-- /SOFTWARE --}}

  {{-- ============================================================
       SERVICES — 4 card dịch vụ
       ============================================================ --}}
  <section id="services" class="section-services section-bg wow fadeInUp">
    <div class="container">

      <div class="section-header text-center">
        <p class="section-eyebrow">{{ tr($settings['sec_services_eyebrow'] ?? '') ?: __('frontend.nav_services') }}</p>
        <h2>{{ tr($settings['sec_services_title'] ?? '') ?: __('frontend.nav_services') }}</h2>
        <p>{{ tr($settings['sec_services_subtitle'] ?? '') ?: __('frontend.seo_default_desc') }}</p>
      </div>

      <div class="row">

        <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.1s">
          <div class="service-card h-100">
            <div class="service-icon">
              <img src="{{ asset('img/smbplus/image.svg') }}" alt="Tailored Technology" width="48" height="48">
            </div>
            <h3>{{ __('frontend.svc_1_title') }}</h3>
            <p>{{ __('frontend.svc_1_desc') }}</p>
            <a href="#contact" class="service-link scrollto">{{ __('frontend.learn_more') }} <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.2s">
          <div class="service-card h-100">
            <div class="service-icon">
              <img src="{{ asset('img/smbplus/image (1).svg') }}" alt="Finance & Trading" width="48" height="48">
            </div>
            <h3>{{ __('frontend.svc_2_title') }}</h3>
            <p>{{ __('frontend.svc_2_desc') }}</p>
            <a href="#contact" class="service-link scrollto">{{ __('frontend.learn_more') }} <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.3s">
          <div class="service-card h-100">
            <div class="service-icon">
              <img src="{{ asset('img/smbplus/image (2).svg') }}" alt="Trading Arrow" width="48" height="48">
            </div>
            <h3>{{ __('frontend.svc_3_title') }}</h3>
            <p>{{ __('frontend.svc_3_desc') }}</p>
            <a href="#contact" class="service-link scrollto">{{ __('frontend.learn_more') }} <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.4s">
          <div class="service-card service-card--featured h-100">
            <div class="service-icon">
              <img src="{{ asset('img/smbplus/image (3).svg') }}" alt="Tài liệu" width="48" height="48">
            </div>
            <h3>{{ __('frontend.svc_4_title') }}</h3>
            <p>{{ __('frontend.svc_4_desc') }}</p>
            <a href="#contact" class="service-link scrollto">{{ __('frontend.learn_more') }} <i class="fa fa-arrow-right"></i></a>
            <div class="service-rating">
              <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  {{-- /SERVICES --}}

  {{-- ============================================================
       PROJECTS / DỰ ÁN TIÊU BIỂU (ĐÃ ẨN THEO YÊU CẦU)
       ============================================================ --}}
  {{-- @if($posts->count())
  <section id="projects" class="section-projects wow fadeInUp">
    <div class="container">
      <div class="section-header text-center">
        <p class="section-eyebrow">{{ $settings['sec_projects_eyebrow'] ?? 'Dự án của chúng tôi' }}</p>
        <h2>{{ $settings['sec_projects_title'] ?? 'Dự án tiêu biểu' }}</h2>
        <p>{{ $settings['sec_projects_subtitle'] ?? 'Những sản phẩm chúng tôi đã xây dựng và triển khai thành công.' }}</p>
      </div>
      <div class="row">
        @foreach($posts->take(3) as $post)
          <div class="col-md-4 mb-4">
            <article class="project-card h-100">
              <a href="{{ route('posts.show', $post->slug) }}" class="d-block">
                @if($post->thumbnail || $post->cover)
                  <div class="project-card-img">
                    <img src="{{ ($post->thumbnail ?? $post->cover)->getUrl('card') }}" alt="{{ $post->title }}">
                  </div>
                @endif
                <div class="project-card-body">
                  <h3>{{ $post->title }}</h3>
                  @if($post->excerpt)
                    <p>{{ $post->excerpt }}</p>
                  @endif
                </div>
              </a>
            </article>
          </div>
        @endforeach
      </div>
      <div class="text-center mt-4">
        <a href="{{ route('posts.index') }}" class="btn-software-primary">Xem tất cả bài viết</a>
      </div>
    </div>
  </section>
  @endif --}}
  {{-- /PROJECTS --}}

  {{-- ============================================================
       INNOVATION — dark bg, 3 card blog
       ============================================================ --}}
  <section id="innovation" class="section-innovation wow fadeInUp">
    <div class="container">

      <div class="innovation-header">
        <p class="section-eyebrow innovation-eyebrow">{{ tr($settings['sec_innovation_eyebrow'] ?? '') ?: __('frontend.news_title') }}</p>
        <h2 class="innovation-title">
          IDEAS THAT DRIVE<br>
          <span>TOMORROW'S INNOVATION</span>
        </h2>
        <p class="innovation-sub">{{ tr($settings['sec_innovation_subtitle'] ?? '') ?: __('frontend.news_sub') }}</p>
      </div>

      <div class="row">

        @if($posts->count() >= 1)
          @php $post1 = $posts->get(0); @endphp
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.1s">
            <article class="innovation-card">
              <a href="{{ route('posts.show', $post1->slug) }}" class="d-block">
                @if($post1->thumbnail || $post1->cover)
                  <div class="innovation-card-img">
                    <img src="{{ ($post1->thumbnail ?? $post1->cover)->getUrl('card') }}" alt="{{ tr($post1->title) }}">
                  </div>
                @else
                  <div class="innovation-card-img innovation-card-img--placeholder">
                    <img src="{{ asset('img/smbplus/hinh-nay-600x360-1.png') }}" alt="{{ tr($post1->title) }}">
                  </div>
                @endif
                <div class="innovation-card-body">
                  <h4>{{ tr($post1->title) }}</h4>
                  @if($post1->excerpt)<p>{{ Str::limit(tr($post1->excerpt), 80) }}</p>@endif
                </div>
              </a>
            </article>
          </div>
        @else
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.1s">
            <article class="innovation-card">
              <div class="innovation-card-img">
                <img src="{{ asset('img/smbplus/hinh-nay-600x360-1.png') }}" alt="{{ __('frontend.news_placeholder_1') }}">
              </div>
              <div class="innovation-card-body">
                <h4>{{ __('frontend.news_placeholder_1') }}</h4>
                <p>{{ __('frontend.news_placeholder_1_sub') }}</p>
              </div>
            </article>
          </div>
        @endif

        @if($posts->count() >= 2)
          @php $post2 = $posts->get(1); @endphp
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.2s">
            <article class="innovation-card">
              <a href="{{ route('posts.show', $post2->slug) }}" class="d-block">
                @if($post2->thumbnail || $post2->cover)
                  <div class="innovation-card-img">
                    <img src="{{ ($post2->thumbnail ?? $post2->cover)->getUrl('card') }}" alt="{{ tr($post2->title) }}">
                  </div>
                @else
                  <div class="innovation-card-img">
                    <img src="{{ asset('img/smbplus/maxresdefault.jpg') }}" alt="{{ tr($post2->title) }}">
                  </div>
                @endif
                <div class="innovation-card-body">
                  <h4>{{ tr($post2->title) }}</h4>
                  @if($post2->excerpt)<p>{{ Str::limit(tr($post2->excerpt), 80) }}</p>@endif
                </div>
              </a>
            </article>
          </div>
        @else
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.2s">
            <article class="innovation-card">
              <div class="innovation-card-img">
                <img src="{{ asset('img/smbplus/maxresdefault.jpg') }}" alt="{{ __('frontend.news_placeholder_2') }}">
              </div>
              <div class="innovation-card-body">
                <h4>{{ __('frontend.news_placeholder_2') }}</h4>
                <p>{{ __('frontend.news_placeholder_2_sub') }}</p>
              </div>
            </article>
          </div>
        @endif

        @if($posts->count() >= 3)
          @php $post3 = $posts->get(2); @endphp
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.3s">
            <article class="innovation-card">
              <a href="{{ route('posts.show', $post3->slug) }}" class="d-block">
                @if($post3->thumbnail || $post3->cover)
                  <div class="innovation-card-img">
                    <img src="{{ ($post3->thumbnail ?? $post3->cover)->getUrl('card') }}" alt="{{ tr($post3->title) }}">
                  </div>
                @else
                  <div class="innovation-card-img">
                    <img src="{{ asset('img/smbplus/8312973a3bf2878f7a1b2c9378420a09dfaf08d9f3b51e7051a1f7b9e328.jpeg') }}" alt="{{ tr($post3->title) }}">
                  </div>
                @endif
                <div class="innovation-card-body">
                  <h4>{{ tr($post3->title) }}</h4>
                  @if($post3->excerpt)<p>{{ Str::limit(tr($post3->excerpt), 80) }}</p>@endif
                </div>
              </a>
            </article>
          </div>
        @else
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.3s">
            <article class="innovation-card">
              <div class="innovation-card-img">
                <img src="{{ asset('img/smbplus/8312973a3bf2878f7a1b2c9378420a09dfaf08d9f3b51e7051a1f7b9e328.jpeg') }}" alt="{{ __('frontend.news_placeholder_3') }}">
              </div>
              <div class="innovation-card-body">
                <h4>{{ __('frontend.news_placeholder_3') }}</h4>
                <p>{{ __('frontend.news_placeholder_3_sub') }}</p>
              </div>
            </article>
          </div>
        @endif

      </div>
    </div>
  </section>
  {{-- /INNOVATION --}}

  {{-- ============================================================
       CTA — Bắt đầu ngay
       ============================================================ --}}
  <section id="cta" class="section-cta wow fadeIn">
    <div class="container text-center">
      <p class="cta-eyebrow">{{ tr($settings['cta_eyebrow'] ?? '') ?: __('frontend.start_now') }}</p>
      <h2 class="cta-title">{{ tr($settings['cta_title'] ?? '') ?: __('frontend.start_now') }}</h2>
      <p class="cta-sub">
        {{ tr($settings['cta_subtitle'] ?? '') ?: __('frontend.seo_default_desc') }}
      </p>
      <div class="cta-actions">
        <a href="#contact" class="btn-cta-primary scrollto">{{ __('frontend.free_consultation') }}</a>
        <a href="{{ route('event') }}" class="btn-cta-secondary">{{ __('frontend.view_event') }}</a>
      </div>
    </div>
  </section>
  {{-- /CTA --}}

  {{-- CONTACT --}}
  @include('sections.contact')

</main>
@endsection
