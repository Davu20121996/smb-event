<section id="gallery" class="wow fadeInUp">

  <div class="container">
    <div class="section-header">
      <h2>{{ __('frontend.gallery') }}</h2>
      <p>{{ __('frontend.gallery_sub') }}</p>
    </div>
  </div>
  @foreach($galleries as $gallery)
    <div class="owl-carousel gallery-carousel">
      @foreach($gallery->photos as $photo)
        <a href="{{ $photo->getUrl() }}" class="venobox" data-gall="gallery-carousel"><img src="{{ $photo->getUrl() }}" alt="{{ tr($gallery->name) }}" title="{{ tr($gallery->name) }}"></a>
      @endforeach
    </div>
  @endforeach
</section>
