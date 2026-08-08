<?php $__env->startSection('seo_title', __('frontend.seo_event_thanks_title', ['name' => $event->name ?? 'SMB+'])); ?>
<?php $__env->startSection('seo_description', __('frontend.seo_event_thanks_desc')); ?>

<?php $__env->startSection('content'); ?>
<main id="main" class="main-page">
  <section id="event-thank-you" class="wow fadeIn section-tight">
    <div class="container">
      <div class="text-center" style="max-width: 640px; margin: 0 auto;">
        <div class="share-thank-icon">
          <i class="fa fa-check-circle-o" aria-hidden="true"></i>
        </div>
        <h2><?php echo e(__('frontend.event_thanks')); ?></h2>
        <p class="body-md mt-3" style="color: var(--ink-muted);">
          <?php echo e(__('frontend.event_thanks_sub')); ?>

        </p>

        <?php if($event->calendar_enabled): ?>
          <?php
            $calTitle = $event->meta_title ?: $event->name;
            $calStart = $event->start_date ? preg_replace('/[^0-9]/', '', $event->start_date) : '';
            $calEnd   = $event->end_date ? preg_replace('/[^0-9]/', '', $event->end_date) : '';
            $googleCal = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . urlencode($calTitle) . '&dates=' . $calStart . '/' . $calEnd . '&details=' . urlencode(strip_tags($event->description ?? ''));
            $outlookCal = 'https://outlook.live.com/calendar/0/action/compose?subject=' . urlencode($calTitle) . '&startdt=' . urlencode($event->start_date) . '&enddt=' . urlencode($event->end_date) . '&body=' . urlencode(strip_tags($event->description ?? ''));
          ?>
          <div class="mt-4">
            <p class="body-sm mb-2" style="color: var(--ink-muted);"><?php echo e(__('frontend.add_to_calendar')); ?></p>
            <div class="event-thank-actions">
              <a href="<?php echo e($googleCal); ?>" target="_blank" rel="noopener" class="btn-software-primary">
                <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Google Calendar
              </a>
              <a href="<?php echo e($outlookCal); ?>" target="_blank" rel="noopener" class="btn-software-primary btn-soft">
                <i class="fa fa-calendar-o" aria-hidden="true"></i> Outlook Calendar
              </a>
            </div>
          </div>
        <?php endif; ?>

        <?php if($event->zalo_url || $event->fanpage_url): ?>
          <div class="mt-4">
            <p class="body-sm mb-2" style="color: var(--ink-muted);"><?php echo e(__('frontend.join_community')); ?></p>
            <div class="event-thank-actions">
              <?php if($event->zalo_url): ?>
                <a href="<?php echo e($event->zalo_url); ?>" target="_blank" rel="noopener" class="btn-software-primary btn-soft">
                  <i class="fa fa-comments" aria-hidden="true"></i> <?php echo e(__('frontend.zalo_community')); ?>

                </a>
              <?php endif; ?>
              <?php if($event->fanpage_url): ?>
                <a href="<?php echo e($event->fanpage_url); ?>" target="_blank" rel="noopener" class="btn-software-primary btn-soft">
                  <i class="fa fa-facebook-square" aria-hidden="true"></i> Fanpage
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="mt-5">
          <a href="<?php echo e(route('event')); ?>" class="text-link"><?php echo __('frontend.back_to_event'); ?></a>
        </div>
      </div>
    </div>
  </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/event-thank-you.blade.php ENDPATH**/ ?>