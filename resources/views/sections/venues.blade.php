<section id="venue" class="wow fadeInUp">
    <div class="container-fluid">
      <div class="section-header">
        <h2>{{ __('frontend.event_venue') }}</h2>
        <p>{{ __('frontend.event_venue_sub') }}</p>
      </div>
    </div>
  @foreach($venues as $venue)
    @php
      $embedSrc = $venue->google_maps_url ?? '';
      if ($embedSrc) {
          if (!str_contains($embedSrc, 'output=embed')) {
              preg_match('/(-?[\d]+\.?\d*)[,\s]+(-?[\d]+\.?\d*)/', $embedSrc, $m);
              $embedSrc = isset($m[1], $m[2])
                  ? 'https://maps.google.com/maps?q=' . $m[1] . ',' . $m[2] . '&hl=en&z=14&output=embed'
                  : 'https://maps.google.com/maps?q=' . urlencode($embedSrc) . '&hl=en&z=14&output=embed';
          }
      } else {
          $embedSrc = 'https://maps.google.com/maps?q=' . $venue->latitude . ',' . $venue->longitude . '&hl=en&z=14&output=embed';
      }
    @endphp
    <div class="row no-gutters">
      <div class="col-lg-6 venue-map">
        <iframe src="{{ $embedSrc }}" frameborder="0" style="border:0" allowfullscreen></iframe>
      </div>

      <div class="col-lg-6 venue-info">
        <div class="row justify-content-center">
          <div class="col-11 col-lg-8">
            <h3>{{ tr($venue->name) }}</h3>
            <p>{{ tr($venue->description) }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid venue-gallery-container">
      <div class="row no-gutters">
        @if($venue->photos)
          @foreach($venue->photos as $photo)
            <div class="col-lg-3 col-md-4">
              <div class="venue-gallery">
                <a href="{{ $photo->getUrl() }}" class="venobox" data-gall="venue-gallery">
                  <img src="{{ $photo->getUrl() }}" alt="" class="img-fluid">
                </a>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  @endforeach
</section>
