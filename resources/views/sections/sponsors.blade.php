<section id="supporters" class="section-with-bg wow fadeInUp">

  <div class="container">
    <div class="section-header">
      <h2>{{ __('frontend.sponsors') }}</h2>
    </div>

    <div class="row no-gutters supporters-wrap clearfix">
      @foreach($sponsors as $sponsor)
        <div class="col-lg-3 col-md-4 col-xs-6">
          <div class="supporter-logo">
            @if($sponsor->logo)
              <img src="{{ $sponsor->logo->getUrl() }}" class="img-fluid" alt="{{ tr($sponsor->name) }}">
            @endif
          </div>
        </div>
      @endforeach
    </div>

  </div>

</section>
