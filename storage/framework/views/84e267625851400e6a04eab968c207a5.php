<section id="venue" class="wow fadeInUp">
    <div class="container-fluid">
      <div class="section-header">
        <h2><?php echo e(__('frontend.event_venue')); ?></h2>
        <p><?php echo e(__('frontend.event_venue_sub')); ?></p>
      </div>
    </div>
  <?php $__currentLoopData = $venues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
      $embedSrc = $venue->google_maps_url ?? '';
      if ($embedSrc) {
          if (!str_contains($embedSrc, 'output=embed')) {
              preg_match('/(-?[\d]+\.?\d*)[,\s]+(-?[\d]+\.?\d*)/', $embedSrc, $m);
              $embedSrc = isset($m[1], $m[2])
                  ? 'https://maps.google.com/maps?q=' . $m[1] . ',' . $m[2] . '&hl=en&z=14&output=embed'
                  : 'https://maps.google.com/maps?q=' . urlencode($embedSrc) . '&hl=en&z=14&output=embed';
          }
      } else {
          $embedSrc = 'https://maps.google.com/maps?q=' . $venue->latitude . ',' . $venue->longitude . '&hl=en&z=14&output=embed';
      }
    ?>
    <div class="row no-gutters">
      <div class="col-lg-6 venue-map">
        <iframe src="<?php echo e($embedSrc); ?>" frameborder="0" style="border:0" allowfullscreen></iframe>
      </div>

      <div class="col-lg-6 venue-info">
        <div class="row justify-content-center">
          <div class="col-11 col-lg-8">
            <h3><?php echo e(tr($venue->name)); ?></h3>
            <p><?php echo e(tr($venue->description)); ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid venue-gallery-container">
      <div class="row no-gutters">
        <?php if($venue->photos): ?>
          <?php $__currentLoopData = $venue->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-md-4">
              <div class="venue-gallery">
                <a href="<?php echo e($photo->getUrl()); ?>" class="venobox" data-gall="venue-gallery">
                  <img src="<?php echo e($photo->getUrl()); ?>" alt="" class="img-fluid">
                </a>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</section>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/sections/venues.blade.php ENDPATH**/ ?>