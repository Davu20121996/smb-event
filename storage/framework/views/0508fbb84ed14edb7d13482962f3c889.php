<section id="faq" class="wow fadeInUp">

  <div class="container">

    <div class="section-header">
      <h2><?php echo e(__('frontend.faq')); ?></h2>
      <p><?php echo e(__('frontend.faq_sub')); ?></p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-9">
          <ul id="faq-list">
            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li>
                <a data-toggle="collapse" class="collapsed" href="#faq<?php echo e($faq->id); ?>"><?php echo e(tr($faq->question)); ?> <i class="fa fa-minus-circle"></i></a>
                <div id="faq<?php echo e($faq->id); ?>" class="collapse" data-parent="#faq-list">
                  <p>
                    <?php echo e(tr($faq->answer)); ?>

                  </p>
                </div>
              </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  
          </ul>
      </div>
    </div>

    <div class="faq-contact text-center">
      <p class="faq-contact-hint"><?php echo e(__('frontend.faq_contact_hint')); ?></p>
      <a href="#contact" class="faq-contact-btn scrollto"><i class="fa fa-comments-o" aria-hidden="true"></i> <?php echo e(__('frontend.faq_contact_btn')); ?></a>
    </div>

  </div>

</section>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/sections/faq.blade.php ENDPATH**/ ?>