<?php if(isset($event) && ($event->pc_bg_image_url || $event->mobile_bg_image_url)): ?>
  <?php $__env->startSection('body_class', 'event-page-body'); ?>
<?php endif; ?>

<?php $__env->startSection('seo_title', trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['<br>', '<br/>', '<br />', '</span>', '</p>', '</h1>'], ' ', tr($settings['title'] ?? ''))))) ?: ($event->meta_title ?: $event->name ?: __('frontend.nav_event'))); ?>
<?php $__env->startSection('seo_description', $event->meta_description ?: __('frontend.seo_event_desc')); ?>
<?php $__env->startSection('seo_image', $event->og_image ?: asset('img/smbplus/logo-1.png')); ?>
<?php if($event->favicon_url): ?>
    <?php $__env->startSection('seo_favicon', $event->favicon_url); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<?php if(isset($event) && ($event->pc_bg_image_url || $event->mobile_bg_image_url)): ?>
  <div class="event-page-bg" aria-hidden="true">
    <?php if($event->mobile_bg_image_url): ?>
      <picture class="event-bg-picture">
        <source media="(max-width: 767px)" srcset="<?php echo e($event->mobile_bg_image_url); ?>">
        <img src="<?php echo e($event->pc_bg_image_url ?: $event->mobile_bg_image_url); ?>" alt="" class="event-bg-img">
      </picture>
    <?php else: ?>
      <img src="<?php echo e($event->pc_bg_image_url); ?>" alt="" class="event-bg-img">
    <?php endif; ?>
    <div class="event-bg-overlay"></div>
  </div>
<?php endif; ?>

<?php echo $__env->make('sections.intro', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<main id="main">
  <?php echo $__env->make('sections.key_benefits', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php echo $__env->make('sections.speakers', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php echo $__env->make('sections.schedule', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php echo $__env->make('sections.venues', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php if($event->show_gallery): ?>
    <?php echo $__env->make('sections.gallery', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php endif; ?>

  <?php if($event->show_sponsors): ?>
    <?php echo $__env->make('sections.sponsors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php endif; ?>

  <?php echo $__env->make('sections.faq', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php if($event->show_tickets): ?>
    <?php echo $__env->make('sections.buy_ticket', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php endif; ?>

  <?php echo $__env->make('sections.event_register', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
  (function () {
    document.documentElement.style.scrollBehavior = 'smooth';
    window.addEventListener('scroll', function () {}, { passive: true });

    var style = document.createElement('style');
    style.textContent = [
      '#main section, #main .container, #main .row { scroll-margin-top: 110px; }',
      'body.event-page-body #main section[id] { scroll-margin-top: 100px; }',
      'body.event-page-body .wow { animation-duration: 1s; }'
    ].join('\n');
    document.head.appendChild(style);
  })();
</script>
<?php if(isset($event) && $event->countdown_enabled && $event->registration_deadline): ?>
<script>
  (function () {
    var deadline = document.getElementById('eventCountdown');
    if (!deadline) return;
    var target = new Date(deadline.getAttribute('data-deadline')).getTime();

    function tick() {
      var now = new Date().getTime();
      var diff = target - now;
      if (diff < 0) diff = 0;
      var days = Math.floor(diff / (1000 * 60 * 60 * 24));
      var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((diff % (1000 * 60)) / 1000);
      var pad = function (n) { return n < 10 ? '0' + n : n; };
      deadline.querySelector('[data-unit="days"]').textContent = pad(days);
      deadline.querySelector('[data-unit="hours"]').textContent = pad(hours);
      deadline.querySelector('[data-unit="minutes"]').textContent = pad(minutes);
      deadline.querySelector('[data-unit="seconds"]').textContent = pad(seconds);
    }
    tick();
    setInterval(tick, 1000);
  })();
</script>
<?php endif; ?>

<style>
  .required-star { color: #e53935; margin-left: 2px; font-weight: bold; }
  .field-error    { border-color: #e53935 !important; }
  .validation.error-msg { color: #e53935; font-size: 12px; margin-top: 4px; display: block; }
</style>
<script>
  (function () {
    var form = document.querySelector('.eventRegisterForm');
    if (!form) return;
    var thankYouUrl = "<?php echo e(route('event.thank-you')); ?>";

    var messages = {
      required : "<?php echo e(__('frontend.event_register_required')); ?>",
      email    : "<?php echo e(__('frontend.event_register_email_invalid')); ?>",
      phone    : "<?php echo e(__('frontend.event_register_phone_invalid')); ?>"
    };

    function showError(input, msg) {
      input.classList.add('field-error');
      var err = input.parentNode.querySelector('.validation');
      if (err) { err.textContent = msg; err.classList.add('error-msg'); }
    }

    function clearError(input) {
      input.classList.remove('field-error');
      var err = input.parentNode.querySelector('.validation');
      if (err) { err.textContent = ''; err.classList.remove('error-msg'); }
    }

    // Live-clear on input
    form.querySelectorAll('input, select').forEach(function (el) {
      el.addEventListener('input', function () { clearError(el); });
      el.addEventListener('change', function () { clearError(el); });
    });

    function validateForm() {
      var ok = true;

      // Họ và tên
      var name = form.querySelector('#er_name');
      if (!name.value.trim()) { showError(name, messages.required); ok = false; }
      else clearError(name);

      // SĐT
      var phone = form.querySelector('#er_phone');
      if (!phone.value.trim()) {
        showError(phone, messages.required); ok = false;
      } else if (!/^[0-9+\-\s()]{7,20}$/.test(phone.value.trim())) {
        showError(phone, messages.phone); ok = false;
      } else clearError(phone);

      // Email
      var email = form.querySelector('#er_email');
      if (!email.value.trim()) {
        showError(email, messages.required); ok = false;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        showError(email, messages.email); ok = false;
      } else clearError(email);

      // Công ty
      var company = form.querySelector('#er_company');
      if (!company.value.trim()) { showError(company, messages.required); ok = false; }
      else clearError(company);

      return ok;
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!validateForm()) return;

      var btn = form.querySelector('button[type="submit"]');
      var originalText = btn.innerHTML;
      btn.innerHTML = 'Sending...';
      btn.disabled = true;

      var xhr = new XMLHttpRequest();
      xhr.open('POST', form.getAttribute('action'), true);
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.setRequestHeader('X-CSRF-TOKEN', form.querySelector('input[name="_token"]').value);

      xhr.send(new FormData(form));

      xhr.onload = function () {
        if (xhr.status === 200) {
          window.location.href = thankYouUrl;
        } else {
          btn.innerHTML = originalText;
          btn.disabled = false;
        }
      };

      xhr.onerror = function () {
        btn.innerHTML = originalText;
        btn.disabled = false;
      };
    });
  })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/event.blade.php ENDPATH**/ ?>