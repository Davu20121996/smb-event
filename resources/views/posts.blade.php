@extends('layouts.main')

@section('seo_title', __('frontend.seo_news_title'))
@section('seo_description', __('frontend.seo_news_desc'))

@section('content')
<main id="main" class="main-page">
  <section id="posts-list" class="wow fadeIn section-tight">
    <div class="container">
      <div class="section-header text-center">
        <p class="section-eyebrow">SMB+ Blog</p>
        @if(isset($tag))
          <h2>{{ __('frontend.posts_title', ['tag' => $tag]) }}</h2>
          <p>{{ __('frontend.posts_tag_sub', ['tag' => $tag]) }}</p>
        @else
          <h2>{!! __('frontend.news_title') !!}</h2>
          <p>{{ __('frontend.news_sub') }}</p>
        @endif
      </div>

      @if($posts->count())
        <div class="row">
          @foreach($posts as $post)
            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp">
              <article class="project-card h-100">
                <a href="{{ route('posts.show', $post->slug) }}" class="d-block">
                  @if($post->thumbnail || $post->cover)
                    <div class="project-card-img">
                      <img src="{{ ($post->thumbnail ?? $post->cover)->getUrl('card') }}" alt="{{ $post->title }}">
                    </div>
                  @endif
                  <div class="project-card-body">
                    @if($post->tag)
                      <span class="post-tag-badge post-tag-badge--small d-inline-flex">{{ tr($post->tag) }}</span>
                    @endif
                    <h3>{{ tr($post->title) }}</h3>
                    @if($post->excerpt)
                      <p>{{ Str::limit(tr($post->excerpt), 90) }}</p>
                    @endif
                  </div>
                </a>
              </article>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center">
          <p class="body-md" style="color: var(--ink-muted);">{{ __('frontend.news_no_posts') }}</p>
        </div>
      @endif
    </div>
  </section>
</main>
@endsection
