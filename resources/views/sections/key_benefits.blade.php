<section id="key-benefits" class="wow fadeInUp">
  <div class="container">
    <div class="section-header">
      <h2>{{ __('frontend.why_attend') }}</h2>
    </div>

    @if(isset($keyBenefits) && $keyBenefits->isNotEmpty())
      <div class="row key-benefits-grid">
        @foreach($keyBenefits as $benefit)
          <div class="col-lg-4 col-md-6">
            <div class="key-benefit">
              @if($benefit->icon_image_url)
                  <img src="{{ $benefit->icon_image_url }}" alt="" class="key-benefit-icon-image">
              @elseif($benefit->icon)
                <div class="key-benefit-icon">
                  <i class="fa {{ $benefit->icon }}" aria-hidden="true"></i>
                </div>
              @endif
              <h3>{{ tr($benefit->title) }}</h3>
              <p>{!! tr($benefit->description) !!}</p>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
