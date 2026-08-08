<section id="subscribe">
  <div class="container wow fadeInUp">
    <div class="section-header">
      <h2>{{ __('frontend.newsletter_title') }}</h2>
      <p>{{ __('frontend.newsletter_sub') }}</p>
    </div>

    <form method="POST" action="#">
      <div class="form-row justify-content-center">
        <div class="col-auto">
          <input type="text" class="form-control" placeholder="{{ __('frontend.newsletter_email_ph') }}">
        </div>
        <div class="col-auto">
          <button type="submit">{{ __('frontend.newsletter_subscribe') }}</button>
        </div>
      </div>
    </form>

  </div>
</section>
