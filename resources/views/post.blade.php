@extends('layouts.main')

@section('seo_title', tr($post->title))
@section('seo_description', tr($post->excerpt ?? '') ?: tr($post->title))
@if($post->cover)
  @section('seo_image', $post->cover->getUrl())
@endif

@section('content')
<main id="main" class="main-page">

  {{-- ============================================================
       POST HERO — tag + title + meta trên nền tối
       ============================================================ --}}
  <section id="post-hero" class="post-hero wow fadeIn">
    <div class="container">
      <nav class="post-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">{{ __('frontend.post_home') }}</a>
        <span class="post-breadcrumb-sep">/</span>
        <a href="{{ route('posts.index') }}">{{ __('frontend.post_news') }}</a>
        @if($post->tag)
          <span class="post-breadcrumb-sep">/</span>
          <a href="{{ route('posts.tag', $post->tag) }}">{{ tr($post->tag) }}</a>
        @endif
      </nav>
      @if($post->tag)
        <a href="{{ route('posts.tag', $post->tag) }}" class="post-tag-badge" style="text-decoration:none;">{{ tr($post->tag) }}</a>
      @endif
      <h1 class="post-title">{{ tr($post->title) }}</h1>
      <div class="post-meta">
        <span><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $post->created_at->format('d/m/Y') }}</span>
        @if($post->tag)
          <span><i class="fa fa-tag" aria-hidden="true"></i> {{ tr($post->tag) }}</span>
        @endif
      </div>
    </div>
  </section>

  {{-- ============================================================
       POST BODY — 2 cột: nội dung (trái) + bài viết liên quan (phải)
       ============================================================ --}}
  <section id="post-body" class="wow fadeIn">
    <div class="container">
      <div class="row">

        {{-- Cột trái: nội dung bài viết --}}
        <div class="col-lg-8">
          <div class="post-main-col">
            <div class="post-featured-wrap">
              @if($post->cover)
                <img src="{{ $post->cover->getUrl('card') }}" alt="{{ $post->title }}" class="post-featured-img">
              @else
                <img src="{{ asset('img/smbplus/hinh-nay-600x360-1.png') }}" alt="{{ $post->title }}" class="post-featured-img">
              @endif
            </div>
            @if($post->excerpt)
              <p class="post-excerpt">{{ tr($post->excerpt) }}</p>
            @endif
            <hr class="post-divider">
            <div class="post-content">
              {!! tr($post->content) !!}
            </div>
          </div>
        </div>

        {{-- Cột phải: bài viết liên quan --}}
        <div class="col-lg-4">
          <aside class="post-sidebar">
            <h4 class="post-sidebar-title">{{ __('frontend.post_related') }}</h4>
            @if($related->count())
              <div class="post-sidebar-list">
                @foreach($related as $item)
                  <article class="post-sidebar-item">
                    @if($item->thumbnail || $item->cover)
                      <a href="{{ route('posts.show', $item->slug) }}" class="d-block">
                        <div class="post-sidebar-thumb">
                          <img src="{{ ($item->thumbnail ?? $item->cover)->getUrl('card') }}" alt="{{ $item->title }}">
                        </div>
                      </a>
                    @endif
                    <div class="post-sidebar-body">
                      @if($item->tag)
                        <a href="{{ route('posts.tag', $item->tag) }}" class="post-tag-badge post-tag-badge--small d-inline-flex" style="text-decoration:none;">{{ tr($item->tag) }}</a>
                      @endif
                      <h5><a href="{{ route('posts.show', $item->slug) }}">{{ tr($item->title) }}</a></h5>
                    </div>
                  </article>
                @endforeach
              </div>
            @else
              <p class="body-sm" style="color: var(--ink-muted);">{{ __('frontend.post_no_related') }}</p>
            @endif

            <div class="post-sidebar-all">
              <a href="{{ route('posts.index') }}" class="btn-software-primary">{{ __('frontend.view_all_posts') }}</a>
            </div>
          </aside>
        </div>

      </div>
    </div>
  </section>
</main>
@endsection
