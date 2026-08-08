<section id="hotels" class="section-with-bg wow fadeInUp">

  <div class="container">
    <div class="section-header">
      <h2>{{ __('frontend.hotels') }}</h2>
      <p>{{ __('frontend.hotels_sub') }}</p>
    </div>

    <div class="row">
      @foreach($hotels as $hotel)
        <div class="col-lg-4 col-md-6">
          <div class="hotel">
            <div class="hotel-img">
              @if($hotel->photo)
                <img src="{{ $hotel->photo->getUrl() }}" alt="{{ tr($hotel->name) }}" class="img-fluid">
              @endif
            </div>
            <h3><a href="#">{{ tr($hotel->name) }}</a></h3>
            <div class="stars">
              @for($i = 0; $i < $hotel->rating; $i++)
                <i class="fa fa-star"></i>
              @endfor
            </div>
            <p>{{ tr($hotel->description) }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</section>
