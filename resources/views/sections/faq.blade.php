<section id="faq" class="wow fadeInUp">

  <div class="container">

    <div class="section-header">
      <h2>{{ __('frontend.faq') }}</h2>
      <p>{{ __('frontend.faq_sub') }}</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-9">
          <ul id="faq-list">
            @foreach($faqs as $faq)
              <li>
                <a data-toggle="collapse" class="collapsed" href="#faq{{ $faq->id }}">{{ tr($faq->question) }} <i class="fa fa-minus-circle"></i></a>
                <div id="faq{{ $faq->id }}" class="collapse" data-parent="#faq-list">
                  <p>
                    {{ tr($faq->answer) }}
                  </p>
                </div>
              </li>
            @endforeach
  
          </ul>
      </div>
    </div>

    <div class="faq-contact text-center">
      <p class="faq-contact-hint">{{ __('frontend.faq_contact_hint') }}</p>
      <a href="#contact" class="faq-contact-btn scrollto"><i class="fa fa-comments-o" aria-hidden="true"></i> {{ __('frontend.faq_contact_btn') }}</a>
    </div>

  </div>

</section>
