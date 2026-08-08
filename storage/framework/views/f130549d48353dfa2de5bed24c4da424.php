<section id="key-benefits" class="wow fadeInUp">
  <div class="container">
    <div class="section-header">
      <h2><?php echo e(__('frontend.why_attend')); ?></h2>
    </div>

    <?php if(isset($keyBenefits) && $keyBenefits->isNotEmpty()): ?>
      <div class="row key-benefits-grid">
        <?php $__currentLoopData = $keyBenefits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $benefit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-lg-4 col-md-6">
            <div class="key-benefit">
              <?php if($benefit->icon_image_url): ?>
                  <img src="<?php echo e($benefit->icon_image_url); ?>" alt="" class="key-benefit-icon-image">
              <?php elseif($benefit->icon): ?>
                <div class="key-benefit-icon">
                  <i class="fa <?php echo e($benefit->icon); ?>" aria-hidden="true"></i>
                </div>
              <?php endif; ?>
              <h3><?php echo e(tr($benefit->title)); ?></h3>
              <p><?php echo tr($benefit->description); ?></p>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/sections/key_benefits.blade.php ENDPATH**/ ?>