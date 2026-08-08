<section id="event-register" class="section-bg register-section wow fadeInUp">

  <div class="register-hero-overlay"></div>

  <div class="container">

    <div class="section-header">
      <h2 class="register-title">{{ __('frontend.event_register_title') }}</h2>
      <p class="register-desc">{{ __('frontend.event_register_sub') }}</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="register-panel">

          <form action="{{ route('event.register-lead') }}" method="post" role="form" class="eventRegisterForm" novalidate>
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id ?? 0 }}">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="er_name">{{ __('frontend.event_register_name') }} <span class="required-star" aria-hidden="true">*</span></label>
                <input type="text" name="name" class="form-control" id="er_name" placeholder="{{ __('frontend.event_register_name') }}" required />
                <div class="validation" id="err_name"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="er_phone">{{ __('frontend.event_register_phone') }} <span class="required-star" aria-hidden="true">*</span></label>
                <input type="text" class="form-control" name="phone" id="er_phone" placeholder="{{ __('frontend.event_register_phone') }}" required />
                <div class="validation" id="err_phone"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="er_email">{{ __('frontend.event_register_email') }} <span class="required-star" aria-hidden="true">*</span></label>
                <input type="email" class="form-control" name="email" id="er_email" placeholder="{{ __('frontend.event_register_email') }}" required />
                <div class="validation" id="err_email"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="er_company">{{ __('frontend.event_register_company') }} <span class="required-star" aria-hidden="true">*</span></label>
                <input type="text" class="form-control" name="company" id="er_company" placeholder="{{ __('frontend.event_register_company') }}" required />
                <div class="validation" id="err_company"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="er_tax_code">{{ __('frontend.event_register_tax_code') }}</label>
                <input type="text" class="form-control" name="tax_code" id="er_tax_code" placeholder="{{ __('frontend.event_register_tax_code') }}" />
                <div class="validation"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="er_company_size">{{ __('frontend.event_register_company_size') }}</label>
                <select class="form-control" name="company_size" id="er_company_size">
                  <option value="">{{ __('frontend.event_register_company_size_placeholder') }}</option>
                  <option value="lt50">{{ __('frontend.event_register_company_size_lt50') }}</option>
                  <option value="50-100">{{ __('frontend.event_register_company_size_50_100') }}</option>
                  <option value="100-200">{{ __('frontend.event_register_company_size_100_200') }}</option>
                  <option value="gt200">{{ __('frontend.event_register_company_size_gt200') }}</option>
                  <option value="organization">{{ __('frontend.event_register_company_size_org') }}</option>
                </select>
                <div class="validation"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="er_products">{{ __('frontend.event_register_products') }}</label>
                <input type="text" class="form-control" name="interested_products" id="er_products" placeholder="{{ __('frontend.event_register_products_placeholder') }}" />
                <div class="validation"></div>
              </div>
            </div>
            <div class="text-center register-submit">
              <button type="submit" class="register-submit-btn">{{ __('frontend.event_register_btn') }} <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
            </div>
          </form>

        </div>
      </div>
    </div>

  </div>

</section><!-- #event-register -->