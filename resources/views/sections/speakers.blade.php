<section id="speakers" class="wow fadeInUp">
  <div class="container">
    <div class="section-header">
      <h2>{{ __('frontend.event_speakers') }}</h2>
      <p>{{ __('frontend.event_speakers_sub') }}</p>
    </div>

    <div class="row">
      @foreach($speakers as $speaker)
        <div class="col-lg-4 col-md-6">
          <div class="speaker">
            @if($speaker->photo)
              <img src="{{ $speaker->photo->getUrl() }}" alt="{{ tr($speaker->name) }}" class="img-fluid">
            @endif
            <div class="details">
              <h3><a href="{{ route('speaker', $speaker->id) }}">{{ tr($speaker->name) }}</a></h3>
              @if($speaker->role)
                <p class="speaker-role">{{ tr($speaker->role) }}</p>
              @endif
              @if($speaker->company)
                <p class="speaker-company">{{ tr($speaker->company) }}</p>
              @endif
              @if($speaker->description)
                <p class="speaker-bio">{{ tr($speaker->description) }}</p>
              @endif
              <div class="social">
                @if($speaker->twitter)
                  <a href="{{ $speaker->twitter }}"><i class="fa fa-twitter"></i></a>
                @endif
                @if($speaker->facebook)
                  <a href="{{ $speaker->facebook }}"><i class="fa fa-facebook"></i></a>
                @endif
                @if($speaker->linkedin)
                  <a href="{{ $speaker->linkedin }}"><i class="fa fa-linkedin"></i></a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</section>
