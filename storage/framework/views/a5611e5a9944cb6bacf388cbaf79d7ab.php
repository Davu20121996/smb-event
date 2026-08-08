<section id="event-register" class="section-bg register-section wow fadeInUp">

  <div class="register-hero-overlay"></div>

  <div class="container">

    <div class="section-header">
      <h2 class="register-title"><?php echo e(__('frontend.event_register_title')); ?></h2>
      <p class="register-desc"><?php echo e(__('frontend.event_register_sub')); ?></p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="register-panel">

          <form action="<?php echo e(route('event.register-lead')); ?>" method="post" role="form" class="eventRegisterForm" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="event_id" value="<?php echo e($event->id ?? 0); ?>">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="er_name"><?php echo e(__('frontend.event_register_name')); ?> <span class="required-star" aria-hidden="true">*</span></label>
                <input type="text" name="name" class="form-control" id="er_name" placeholder="<?php echo e(__('frontend.event_register_name')); ?>" required />
                <div class="validation" id="err_name"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="er_phone"><?php echo e(__('frontend.event_register_phone')); ?> <span class="required-star" aria-hidden="true">*</span></label>
                <input type="text" class="form-control" name="phone" id="er_phone" placeholder="<?php echo e(__('frontend.event_register_phone')); ?>" required />
                <div class="validation" id="err_phone"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="er_email"><?php echo e(__('frontend.event_register_email')); ?> <span class="required-star" aria-hidden="true">*</span></label>
                <input type="email" class="form-control" name="email" id="er_email" placeholder="<?php echo e(__('frontend.event_register_email')); ?>" required />
                <div class="validation" id="err_email"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="er_company"><?php echo e(__('frontend.event_register_company')); ?> <span class="required-star" aria-hidden="true">*</span></label>
                <input type="text" class="form-control" name="company" id="er_company" placeholder="<?php echo e(__('frontend.event_register_company')); ?>" required />
                <div class="validation" id="err_company"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="er_tax_code"><?php echo e(__('frontend.event_register_tax_code')); ?></label>
                <input type="text" class="form-control" name="tax_code" id="er_tax_code" placeholder="<?php echo e(__('frontend.event_register_tax_code')); ?>" />
                <div class="validation"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="er_company_size"><?php echo e(__('frontend.event_register_company_size')); ?></label>
                <select class="form-control" name="company_size" id="er_company_size">
                  <option value=""><?php echo e(__('frontend.event_register_company_size_placeholder')); ?></option>
                  <option value="lt50"><?php echo e(__('frontend.event_register_company_size_lt50')); ?></option>
                  <option value="50-100"><?php echo e(__('frontend.event_register_company_size_50_100')); ?></option>
                  <option value="100-200"><?php echo e(__('frontend.event_register_company_size_100_200')); ?></option>
                  <option value="gt200"><?php echo e(__('frontend.event_register_company_size_gt200')); ?></option>
                  <option value="organization"><?php echo e(__('frontend.event_register_company_size_org')); ?></option>
                </select>
                <div class="validation"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="er_products"><?php echo e(__('frontend.event_register_products')); ?></label>
                <input type="text" class="form-control" name="interested_products" id="er_products" placeholder="<?php echo e(__('frontend.event_register_products_placeholder')); ?>" />
                <div class="validation"></div>
              </div>
            </div>
            <div class="text-center register-submit">
              <button type="submit" class="register-submit-btn"><?php echo e(__('frontend.event_register_btn')); ?> <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
            </div>
          </form>

        </div>
      </div>
    </div>

  </div>

</section><!-- #event-register --><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/sections/event_register.blade.php ENDPATH**/ ?>