@extends('layouts.main')

@section('seo_title', __('frontend.seo_freshwork_title'))
@section('seo_description', __('frontend.seo_freshwork_desc'))

@section('content')
<main id="main" class="main-page">
  <section id="freshwork" class="freshwork-page wow fadeIn">
    <iframe
      width="100%"
      height="2255"
      style="top:0px; left:0px; border:0"
      gesture="media"
      allow="encrypted-media"
      frameborder="0"
      allowfullscreen=""
      src="https://freshworks.smbplus.vn/"
      data-gtm-yt-inspected-11="true"
      title="Freshwork"
    ></iframe>
  </section>
</main>
@endsection
