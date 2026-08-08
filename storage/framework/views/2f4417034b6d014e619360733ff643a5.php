<?php
    $routeName = Route::currentRouteName();
    $isEventPage = in_array($routeName, ['event', 'event.show', 'speaker']);
    $base = $isEventPage ? route('event') : route('home');
?>


<div id="topbar">
  <div class="container d-flex align-items-center justify-content-between">
    <div class="topbar-left d-flex align-items-center gap-3">
      <?php if($settings['contact_phone'] ?? ''): ?>
        <span class="topbar-item">
          <i class="fa fa-phone" aria-hidden="true"></i>
          <a href="tel:<?php echo e(str_replace(' ', '', $settings['contact_phone'])); ?>"><?php echo e($settings['contact_phone']); ?></a>
        </span>
      <?php endif; ?>
      <?php if($settings['contact_email'] ?? ''): ?>
        <span class="topbar-item">
          <i class="fa fa-envelope-o" aria-hidden="true"></i>
          <a href="mailto:<?php echo e($settings['contact_email']); ?>"><?php echo e($settings['contact_email']); ?></a>
        </span>
      <?php endif; ?>
    </div>
    <div class="topbar-right d-flex align-items-center gap-2">
      <?php if($settings['footer_facebook'] ?? ''): ?>
        <a href="<?php echo e($settings['footer_facebook']); ?>" class="topbar-social" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
      <?php endif; ?>
      <?php if($settings['footer_twitter'] ?? ''): ?>
        <a href="<?php echo e($settings['footer_twitter']); ?>" class="topbar-social" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
      <?php endif; ?>
      <?php if($settings['footer_linkedin'] ?? ''): ?>
        <a href="<?php echo e($settings['footer_linkedin']); ?>" class="topbar-social" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
      <?php endif; ?>
    </div>
  </div>
</div>

<header id="header" class="nav-bar">
  <div class="container d-flex align-items-center justify-content-between">

    <div id="logo" class="pull-left">
      <a href="<?php echo e(route('home')); ?>" class="logo-link">
        <img src="<?php echo e(asset('img/smbplus/logo-1.png')); ?>" alt="<?php echo e(config('app.name', 'SMB+')); ?>" class="logo-img">
      </a>
    </div>

    <nav id="nav-menu-container">
      <ul class="nav-menu">
        <?php if($isEventPage): ?>
          <li class="menu-active"><a href="<?php echo e($base); ?>#intro"><?php echo e(__('frontend.nav_home')); ?></a></li>
          <li><a href="<?php echo e($base); ?>#speakers"><?php echo e(__('frontend.nav_speakers')); ?></a></li>
          <li><a href="<?php echo e($base); ?>#schedule"><?php echo e(__('frontend.nav_schedule')); ?></a></li>
          <li><a href="<?php echo e($base); ?>#venue"><?php echo e(__('frontend.nav_venue')); ?></a></li>
          <li><a href="<?php echo e($base); ?>#contact"><?php echo e(__('frontend.nav_contact')); ?></a></li>
        <?php else: ?>
          <?php $__empty_1 = true; $__currentLoopData = $navMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if($menu->children->count()): ?>
              <li class="menu-has-children">
                <a href="<?php echo e($menu->url ?: '#'); ?>"><?php echo e(tr($menu->label)); ?></a>
                <ul class="nav-menu-sub">
                  <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                      <a href="<?php echo e($child->url ?: '#'); ?>"><?php echo e(tr($child->label)); ?></a>
                    </li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
              </li>
            <?php else: ?>
              <li>
                <a href="<?php echo e($menu->url ?: '#'); ?>"><?php echo e(tr($menu->label)); ?></a>
              </li>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="menu-active"><a href="<?php echo e($base); ?>#intro"><?php echo e(__('frontend.nav_home')); ?></a></li>
            <li><a href="<?php echo e($base); ?>#about"><?php echo e(__('frontend.nav_about')); ?></a></li>
            <li><a href="<?php echo e($base); ?>#services"><?php echo e(__('frontend.nav_services')); ?></a></li>
            <li><a href="<?php echo e(route('event')); ?>"><?php echo e(__('frontend.nav_event')); ?></a></li>
            <li><a href="<?php echo e($base); ?>#projects"><?php echo e(__('frontend.nav_projects')); ?></a></li>
            <li><a href="<?php echo e(route('posts.index')); ?>"><?php echo e(__('frontend.nav_news')); ?></a></li>
            <li><a href="<?php echo e($base); ?>#contact"><?php echo e(__('frontend.nav_contact')); ?></a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </nav>

    <div class="nav-actions d-flex align-items-center gap-2">
      <div class="nav-lang-switcher" id="navLangSwitcher">
        <button type="button" class="nav-lang-current" aria-haspopup="true" aria-expanded="false" aria-label="<?php echo e(__('frontend.language')); ?>">
          <span class="nav-lang-globe" aria-hidden="true"><i class="fa fa-globe"></i></span>
          <span class="nav-lang-code"><?php echo e(strtoupper(app()->getLocale())); ?></span>
          <span class="nav-lang-caret" aria-hidden="true"><i class="fa fa-caret-down"></i></span>
        </button>
        <ul class="nav-lang-dropdown" role="menu">
          <?php $__currentLoopData = config('panel.available_languages', ['vi' => 'Tiếng Việt', 'en' => 'English']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $langLocale => $langName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li role="none">
              <form method="POST" action="<?php echo e(route('locale.switch')); ?>" class="nav-lang-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="locale" value="<?php echo e($langLocale); ?>">
                <button type="submit" role="menuitem" class="nav-lang-item <?php echo e(app()->getLocale() === $langLocale ? 'active' : ''); ?>">
                  <span class="nav-lang-name"><?php echo e($langName); ?></span>
                  <?php if(app()->getLocale() === $langLocale): ?>
                    <span class="nav-lang-check" aria-hidden="true"><i class="fa fa-check"></i></span>
                  <?php endif; ?>
                </button>
              </form>
            </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
      <?php if($isEventPage): ?>
        <a class="nav-contact-btn nav-register-btn" href="<?php echo e($base); ?>#buy-tickets"><?php echo e(__('frontend.register_now')); ?></a>
      <?php else: ?>
        <a class="nav-contact-btn" href="#contact"><?php echo e(__('frontend.nav_contact_now')); ?></a>
      <?php endif; ?>
    </div>

  </div>
</header>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/partials/header.blade.php ENDPATH**/ ?>