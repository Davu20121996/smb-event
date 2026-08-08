<section id="contact" class="section-bg register-section wow fadeInUp">

  <div class="register-hero-overlay"></div>

  <div class="container">

    <div class="section-header">
      <h2 class="register-title"><?php echo e(__('frontend.contact_us')); ?></h2>
      <p class="register-desc"><?php echo e(__('frontend.contact_us_sub')); ?></p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="register-panel">

          <form action="<?php echo e(route('contact.send')); ?>" method="post" role="form" class="contactForm" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="event_id" value="<?php echo e($event->id ?? 0); ?>">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="er_name"><?php echo e(__('frontend.event_register_name')); ?></label>
                <input type="text" name="name" class="form-control" id="er_name" placeholder="<?php echo e(__('frontend.event_register_name')); ?>" data-rule="minlen:2" data-msg="Vui lòng nhập họ và tên hợp lệ" required />
                <div class="validation"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="er_email"><?php echo e(__('frontend.event_register_email')); ?></label>
                <input type="email" class="form-control" name="email" id="er_email" placeholder="<?php echo e(__('frontend.event_register_email')); ?>" data-rule="email" data-msg="Vui lòng nhập email hợp lệ" required />
                <div class="validation"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="er_company"><?php echo e(__('frontend.event_register_company')); ?></label>
                <input type="text" class="form-control" name="company" id="er_company" placeholder="<?php echo e(__('frontend.event_register_company')); ?>" />
                <div class="validation"></div>
              </div>
              <div class="form-group col-md-6">
                <label for="er_phone"><?php echo e(__('frontend.event_register_phone')); ?></label>
                <input type="text" class="form-control" name="phone" id="er_phone" placeholder="<?php echo e(__('frontend.event_register_phone')); ?>" />
                <div class="validation"></div>
              </div>
            </div>
            <div class="text-center register-submit">
              <button type="submit" class="register-submit-btn"><?php echo e(__('frontend.event_register_btn')); ?> <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
            </div>
          </form>

          <div class="register-success-banner" id="registerSuccessBanner" style="display:none;">
            <div class="register-success-icon"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
            <h4 class="register-success-title"><?php echo e(__('frontend.event_register_success_title')); ?></h4>
            <p class="register-success-text"><?php echo e(__('frontend.event_register_success_text')); ?></p>
            <button type="button" class="register-success-close" id="registerSuccessClose">&times;</button>
          </div>

        </div>
      </div>
    </div>

  </div>

</section><!-- #contact -->
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/sections/contact.blade.php ENDPATH**/ ?>