<?php
    // PC background: ưu tiên ảnh bg được thêm trong event admin, fallback default ảnh tối
    $heroImage = null;
    if (isset($event)) {
        $heroImage = $event->pc_bg_image_url ?: $event->mobile_bg_image_url;
    }
    if (!$heroImage) {
        $heroImage = asset('img/smbplus/tirza-van-dijk-58298-unsplash.jpg');
    }

    // Mobile background: ảnh riêng cho mobile, nếu không có thì dùng chung PC image
    $mobileHeroImage = (isset($event) && $event->mobile_bg_image_url)
        ? $event->mobile_bg_image_url
        : null;
?>

<section id="intro" class="home-hero">
  <div class="hero-inner">
    <div class="hero-bg">

      
      <?php if($mobileHeroImage): ?>
        <picture class="hero-picture">
          <source media="(max-width: 767px)" srcset="<?php echo e($mobileHeroImage); ?>">
          <img src="<?php echo e($heroImage); ?>"
               alt="<?php echo e(tr($settings['title'] ?? '')); ?>"
               class="hero-img"
               loading="eager">
        </picture>
      <?php else: ?>
        <img src="<?php echo e($heroImage); ?>"
             alt="<?php echo e(tr($settings['title'] ?? '')); ?>"
             class="hero-img"
             loading="eager">
      <?php endif; ?>

      <div class="hero-overlay" aria-hidden="true"></div>

      <div class="hero-content wow fadeInLeft">
        <h1 class="hero-title"><?php echo tr($settings['title'] ?? ''); ?></h1>
        <p class="hero-tagline"><?php echo e(__('frontend.hero_tagline')); ?></p>
        <p class="hero-sub"><?php echo e(tr($settings['subtitle'] ?? '')); ?></p>

        <?php if(isset($event) && $event->countdown_enabled && $event->registration_deadline): ?>
          <div class="event-countdown" id="eventCountdown"
               data-deadline="<?php echo e($event->registration_deadline->toIso8601String()); ?>">
            <div class="countdown-box">
              <span class="countdown-num" data-unit="days">00</span>
              <span class="countdown-label"><?php echo e(__('frontend.countdown_days')); ?></span>
            </div>
            <div class="countdown-box">
              <span class="countdown-num" data-unit="hours">00</span>
              <span class="countdown-label"><?php echo e(__('frontend.countdown_hours')); ?></span>
            </div>
            <div class="countdown-box">
              <span class="countdown-num" data-unit="minutes">00</span>
              <span class="countdown-label"><?php echo e(__('frontend.countdown_minutes')); ?></span>
            </div>
            <div class="countdown-box">
              <span class="countdown-num" data-unit="seconds">00</span>
              <span class="countdown-label"><?php echo e(__('frontend.countdown_seconds')); ?></span>
            </div>
          </div>
        <?php endif; ?>

        <div class="hero-cta">
          <a href="#event-register" class="btn-hero-primary scrollto">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            <?php echo e(__('frontend.register_now')); ?>

          </a>
          <a href="#schedule" class="btn-hero-agenda scrollto">
            <i class="fa fa-calendar" aria-hidden="true"></i>
            <?php echo e(__('frontend.hero_view_agenda')); ?>

          </a>
        </div>
      </div>

    </div>
  </div>
</section>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/sections/intro.blade.php ENDPATH**/ ?>