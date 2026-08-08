<footer id="footer">

  <div class="footer-main">
    <div class="container">
      <div class="row">

        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="footer-brand">
            <a href="<?php echo e(route('home')); ?>" class="footer-logo-link">
              <img src="<?php echo e(asset('img/smbplus/logo-1.png')); ?>" alt="<?php echo e(config('app.name', 'SMB+')); ?>" class="footer-logo">
            </a>
            <ul class="footer-contact-list">
              <?php if(($settings['footer_address'] ?? '') || ($settings['contact_address'] ?? '')): ?>
                <li>
                  <i class="fa fa-map-marker" aria-hidden="true"></i>
                  <span><?php echo tr($settings['footer_address'] ?? '') ?: tr($settings['contact_address'] ?? ''); ?></span>
                </li>
              <?php endif; ?>
              <?php if($settings['contact_phone'] ?? ''): ?>
                <li>
                  <i class="fa fa-phone" aria-hidden="true"></i>
                  <a href="tel:<?php echo e(str_replace(' ', '', $settings['contact_phone'])); ?>"><?php echo e($settings['contact_phone']); ?></a>
                </li>
              <?php endif; ?>
              <?php if($settings['contact_email'] ?? ''): ?>
                <li>
                  <i class="fa fa-envelope-o" aria-hidden="true"></i>
                  <a href="mailto:<?php echo e($settings['contact_email']); ?>"><?php echo e($settings['contact_email']); ?></a>
                </li>
              <?php endif; ?>
            </ul>
            <h4 class="footer-heading"><?php echo e(__('frontend.footer_follow_us')); ?></h4>
            <div class="footer-social">
              <?php if($settings['footer_facebook'] ?? ''): ?>
                <a href="<?php echo e($settings['footer_facebook']); ?>" class="footer-social-link" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
              <?php endif; ?>
              <?php if($settings['footer_linkedin'] ?? ''): ?>
                <a href="<?php echo e($settings['footer_linkedin']); ?>" class="footer-social-link" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
              <?php endif; ?>
              <?php if($settings['footer_instagram'] ?? ''): ?>
                <a href="<?php echo e($settings['footer_instagram']); ?>" class="footer-social-link" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
              <?php endif; ?>
              <?php if($settings['footer_twitter'] ?? ''): ?>
                <a href="<?php echo e($settings['footer_twitter']); ?>" class="footer-social-link" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
              <?php endif; ?>
              <?php if(!($settings['footer_facebook'] ?? '') && !($settings['footer_linkedin'] ?? '') && !($settings['footer_instagram'] ?? '') && !($settings['footer_twitter'] ?? '')): ?>
                <a href="#" class="footer-social-link" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                <a href="#" class="footer-social-link" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
                <a href="#" class="footer-social-link" aria-label="YouTube"><i class="fa fa-youtube-play"></i></a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        
        <div class="col-lg-3 col-md-6 mb-4">
          <h4 class="footer-heading"><?php echo e(__('frontend.footer_services')); ?></h4>
          <ul class="footer-links">
            <li><a href="<?php echo e(route('home')); ?>#services">Software Engineering</a></li>
            <li><a href="<?php echo e(route('home')); ?>#services">Technology Consulting</a></li>
            <li><a href="<?php echo e(route('home')); ?>#services">Testing service</a></li>
          </ul>
        </div>

        
        <div class="col-lg-3 col-md-6 mb-4">
          <h4 class="footer-heading"><?php echo e(__('frontend.footer_solutions')); ?></h4>
          <ul class="footer-links">
            <li><a href="<?php echo e(route('home')); ?>#software">HRM+</a></li>
            <li><a href="<?php echo e(route('home')); ?>#software">Freshworks</a></li>
            <li><a href="<?php echo e(route('home')); ?>#projects">Case Studies</a></li>
          </ul>
        </div>

        
        <div class="col-lg-2 col-md-6 mb-4">
          <h4 class="footer-heading"><?php echo e(__('frontend.footer_explore')); ?></h4>
          <ul class="footer-links">
            <li><a href="<?php echo e(route('posts.index')); ?>"><?php echo e(__('frontend.footer_news')); ?></a></li>
            <li><a href="<?php echo e(route('home')); ?>#contact"><?php echo e(__('frontend.footer_contact')); ?></a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container text-center">
      <p class="mb-0">Copyright &copy; 2026 <strong>SMB+</strong></p>
    </div>
  </div>

</footer>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/partials/footer.blade.php ENDPATH**/ ?>