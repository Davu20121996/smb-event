<section id="speakers" class="wow fadeInUp">
  <div class="container">
    <div class="section-header">
      <h2><?php echo e(__('frontend.event_speakers')); ?></h2>
      <p><?php echo e(__('frontend.event_speakers_sub')); ?></p>
    </div>

    <div class="row">
      <?php $__currentLoopData = $speakers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speaker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-4 col-md-6">
          <div class="speaker">
            <?php if($speaker->photo): ?>
              <img src="<?php echo e($speaker->photo->getUrl()); ?>" alt="<?php echo e(tr($speaker->name)); ?>" class="img-fluid">
            <?php endif; ?>
            <div class="details">
              <h3><a href="<?php echo e(route('speaker', $speaker->id)); ?>"><?php echo e(tr($speaker->name)); ?></a></h3>
              <?php if($speaker->role): ?>
                <p class="speaker-role"><?php echo e(tr($speaker->role)); ?></p>
              <?php endif; ?>
              <?php if($speaker->company): ?>
                <p class="speaker-company"><?php echo e(tr($speaker->company)); ?></p>
              <?php endif; ?>
              <?php if($speaker->description): ?>
                <p class="speaker-bio"><?php echo e(tr($speaker->description)); ?></p>
              <?php endif; ?>
              <div class="social">
                <?php if($speaker->twitter): ?>
                  <a href="<?php echo e($speaker->twitter); ?>"><i class="fa fa-twitter"></i></a>
                <?php endif; ?>
                <?php if($speaker->facebook): ?>
                  <a href="<?php echo e($speaker->facebook); ?>"><i class="fa fa-facebook"></i></a>
                <?php endif; ?>
                <?php if($speaker->linkedin): ?>
                  <a href="<?php echo e($speaker->linkedin); ?>"><i class="fa fa-linkedin"></i></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>

</section>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/sections/speakers.blade.php ENDPATH**/ ?>