<section id="about">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h2>{{ __('frontend.about_event') }}</h2>
        <p>{{ tr($event->about_description ?? '') ?: tr($settings['about_description'] ?? '') }}</p>
      </div>
      <div class="col-lg-3">
        <h3>{{ __('frontend.about_where') }}</h3>
        <p>{!! tr($event->about_where ?? '') ?: tr($settings['about_where'] ?? '') !!}</p>
      </div>
      <div class="col-lg-3">
        <h3>{{ __('frontend.about_when') }}</h3>
        <p>{!! tr($event->about_when ?? '') ?: tr($settings['about_when'] ?? '') !!}</p>
      </div>
    </div>
  </div>
</section>
