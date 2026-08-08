<section id="schedule" class="section-with-bg">
  <div class="container wow fadeInUp">
    <div class="section-header">
      <h2><?php echo e(__('frontend.event_schedule')); ?></h2>
      <p><?php echo e(__('frontend.event_schedule_sub')); ?></p>
    </div>

    <ul class="nav nav-tabs" role="tablist">
      <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="nav-item">
          <a class="nav-link<?php echo e($key === 1 ? ' active' : ''); ?>" href="#day-<?php echo e($key); ?>" role="tab" data-toggle="tab"><?php echo e(__('frontend.schedule_day')); ?> <?php echo e($key); ?></a>
        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>

    <div class="tab-content row justify-content-center">
      <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div role="tabpanel" class="col-lg-9 tab-pane fade<?php echo e($key === 1 ? ' show active' : ''); ?>" id="day-<?php echo e($key); ?>">
          <?php $__currentLoopData = $day; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="row schedule-item">
              <div class="col-md-2">
                <div class="schedule-time">
                  <i class="fa fa-microphone" aria-hidden="true"></i>
                  <time><?php echo e(\Carbon\Carbon::parse($schedule->start_time)->format("h:i A")); ?></time>
                </div>
              </div>
              <div class="col-md-10">
                <?php if($schedule->speaker): ?>
                  <div class="speaker">
                    <img src="<?php echo e($schedule->speaker->photo->getUrl()); ?>" alt="<?php echo e(tr($schedule->speaker->name)); ?>">
                  </div>
                <?php endif; ?>
                <h4><?php echo e(tr($schedule->title)); ?> <?php if($schedule->speaker): ?><span><?php echo e(tr($schedule->speaker->name)); ?></span><?php endif; ?></h4>
                <?php if($schedule->subtitle): ?>
                  <p class="schedule-subtitle"><?php echo e(tr($schedule->subtitle)); ?></p>
                <?php endif; ?>
                <?php if($schedule->desc): ?>
                  <p class="schedule-desc"><?php echo tr($schedule->desc); ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</section>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/sections/schedule.blade.php ENDPATH**/ ?>