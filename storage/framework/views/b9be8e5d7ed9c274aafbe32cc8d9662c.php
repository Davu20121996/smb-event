<?php $__env->startSection('seo_title', tr($settings['seo_title'] ?? '') ?: __('frontend.seo_home_title')); ?>
<?php $__env->startSection('seo_description', tr($settings['seo_description'] ?? '') ?: __('frontend.seo_home_desc')); ?>
<?php $__env->startSection('seo_keywords', tr($settings['seo_keywords'] ?? '') ?: __('frontend.seo_home_keywords')); ?>
<?php $__env->startSection('seo_image', asset('img/smbplus/slider_d2.jpg')); ?>

<?php $__env->startSection('content'); ?>


<section id="intro" class="home-hero hero-home" aria-label="Trang chủ SMB+">
  <div class="hero-inner">
    <div class="hero-bg">
      <img src="<?php echo e(asset('img/smbplus/slider_d2.jpg')); ?>"
           alt="SMB+ — Giải pháp phần mềm quản lý doanh nghiệp"
           class="hero-img"
           loading="eager">
      <div class="hero-overlay" aria-hidden="true"></div>

      <div class="hero-content wow fadeInLeft">
        <p class="hero-eyebrow"><?php echo e(__('frontend.hero_eyebrow')); ?></p>
        <h1 class="hero-title">
          <?php echo tr($settings['company_title'] ?? '') ?: __('frontend.seo_default_title'); ?>

        </h1>
        <p class="hero-sub">
          <?php echo e(tr($settings['company_subtitle'] ?? '') ?: __('frontend.software_headline')); ?>

        </p>
        <div class="hero-cta">
          <a href="#software" class="btn-hero-primary scrollto"><?php echo e(__('frontend.hero_explore')); ?></a>
          <?php if($settings['company_youtube_link'] ?? ''): ?>
            <a href="<?php echo e($settings['company_youtube_link']); ?>" class="btn-hero-play venobox" data-vbtype="video" data-autoplay="true" aria-label="Xem video giới thiệu SMB+">
              <span class="play-circle"><i class="fa fa-play" aria-hidden="true"></i></span>
              <span class="play-label"><?php echo e(__('frontend.hero_watch_video')); ?></span>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<main id="main">

  
  <section id="software" class="section-software wow fadeInUp">
    <div class="container">
      <div class="row align-items-center">

        
        <div class="col-lg-5 col-md-12 mb-4 mb-lg-0">
          <p class="section-eyebrow"><?php echo e(tr($settings['sec_software_eyebrow'] ?? '') ?: __('frontend.software_title')); ?></p>
          <h2 class="section-title-left"><?php echo tr($settings['sec_software_title'] ?? '') ?: __('frontend.software_headline'); ?></h2>
          <p class="section-desc">
            <?php echo e(tr($settings['company_about'] ?? '') ?: __('frontend.seo_default_desc')); ?>

          </p>
          <div class="software-cta-group">
            <a href="#contact" class="btn-software-primary scrollto"><?php echo e(__('frontend.contact_consult')); ?></a>
            <?php if($settings['contact_phone'] ?? ''): ?>
              <a href="tel:<?php echo e(str_replace(' ', '', $settings['contact_phone'])); ?>" class="btn-software-phone">
                <i class="fa fa-phone-square" aria-hidden="true"></i>
                <?php echo e($settings['contact_phone']); ?>

              </a>
            <?php endif; ?>
          </div>
        </div>

        
        <div class="col-lg-7 col-md-12">
          <div class="steps-list">

            <div class="step-item wow fadeInRight" data-wow-delay="0.1s">
              <div class="step-number">1</div>
              <div class="step-content">
                <h4><?php echo e(tr($settings['step1_title'] ?? '') ?: __('frontend.step1_title')); ?></h4>
                <p><?php echo e(tr($settings['step1_desc'] ?? '') ?: __('frontend.step1_desc')); ?></p>
              </div>
            </div>

            <div class="step-item wow fadeInRight" data-wow-delay="0.2s">
              <div class="step-number">2</div>
              <div class="step-content">
                <h4><?php echo e(tr($settings['step2_title'] ?? '') ?: __('frontend.step2_title')); ?></h4>
                <p><?php echo e(tr($settings['step2_desc'] ?? '') ?: __('frontend.step2_desc')); ?></p>
              </div>
            </div>

            <div class="step-item wow fadeInRight" data-wow-delay="0.3s">
              <div class="step-number">3</div>
              <div class="step-content">
                <h4><?php echo e(tr($settings['step3_title'] ?? '') ?: __('frontend.step3_title')); ?></h4>
                <p><?php echo e(tr($settings['step3_desc'] ?? '') ?: __('frontend.step3_desc')); ?></p>
              </div>
            </div>

            <div class="step-item wow fadeInRight" data-wow-delay="0.4s">
              <div class="step-number">4</div>
              <div class="step-content">
                <h4><?php echo e(tr($settings['step4_title'] ?? '') ?: __('frontend.step4_title')); ?></h4>
                <p><?php echo e(tr($settings['step4_desc'] ?? '') ?: __('frontend.step4_desc')); ?></p>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
  

  
  <section id="services" class="section-services section-bg wow fadeInUp">
    <div class="container">

      <div class="section-header text-center">
        <p class="section-eyebrow"><?php echo e(tr($settings['sec_services_eyebrow'] ?? '') ?: __('frontend.nav_services')); ?></p>
        <h2><?php echo e(tr($settings['sec_services_title'] ?? '') ?: __('frontend.nav_services')); ?></h2>
        <p><?php echo e(tr($settings['sec_services_subtitle'] ?? '') ?: __('frontend.seo_default_desc')); ?></p>
      </div>

      <div class="row">

        <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.1s">
          <div class="service-card h-100">
            <div class="service-icon">
              <img src="<?php echo e(asset('img/smbplus/image.svg')); ?>" alt="Tailored Technology" width="48" height="48">
            </div>
            <h3><?php echo e(__('frontend.svc_1_title')); ?></h3>
            <p><?php echo e(__('frontend.svc_1_desc')); ?></p>
            <a href="#contact" class="service-link scrollto"><?php echo e(__('frontend.learn_more')); ?> <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.2s">
          <div class="service-card h-100">
            <div class="service-icon">
              <img src="<?php echo e(asset('img/smbplus/image (1).svg')); ?>" alt="Finance & Trading" width="48" height="48">
            </div>
            <h3><?php echo e(__('frontend.svc_2_title')); ?></h3>
            <p><?php echo e(__('frontend.svc_2_desc')); ?></p>
            <a href="#contact" class="service-link scrollto"><?php echo e(__('frontend.learn_more')); ?> <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.3s">
          <div class="service-card h-100">
            <div class="service-icon">
              <img src="<?php echo e(asset('img/smbplus/image (2).svg')); ?>" alt="Trading Arrow" width="48" height="48">
            </div>
            <h3><?php echo e(__('frontend.svc_3_title')); ?></h3>
            <p><?php echo e(__('frontend.svc_3_desc')); ?></p>
            <a href="#contact" class="service-link scrollto"><?php echo e(__('frontend.learn_more')); ?> <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.4s">
          <div class="service-card service-card--featured h-100">
            <div class="service-icon">
              <img src="<?php echo e(asset('img/smbplus/image (3).svg')); ?>" alt="Tài liệu" width="48" height="48">
            </div>
            <h3><?php echo e(__('frontend.svc_4_title')); ?></h3>
            <p><?php echo e(__('frontend.svc_4_desc')); ?></p>
            <a href="#contact" class="service-link scrollto"><?php echo e(__('frontend.learn_more')); ?> <i class="fa fa-arrow-right"></i></a>
            <div class="service-rating">
              <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  

  
  
  

  
  <section id="innovation" class="section-innovation wow fadeInUp">
    <div class="container">

      <div class="innovation-header">
        <p class="section-eyebrow innovation-eyebrow"><?php echo e(tr($settings['sec_innovation_eyebrow'] ?? '') ?: __('frontend.news_title')); ?></p>
        <h2 class="innovation-title">
          IDEAS THAT DRIVE<br>
          <span>TOMORROW'S INNOVATION</span>
        </h2>
        <p class="innovation-sub"><?php echo e(tr($settings['sec_innovation_subtitle'] ?? '') ?: __('frontend.news_sub')); ?></p>
      </div>

      <div class="row">

        <?php if($posts->count() >= 1): ?>
          <?php $post1 = $posts->get(0); ?>
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.1s">
            <article class="innovation-card">
              <a href="<?php echo e(route('posts.show', $post1->slug)); ?>" class="d-block">
                <?php if($post1->thumbnail || $post1->cover): ?>
                  <div class="innovation-card-img">
                    <img src="<?php echo e(($post1->thumbnail ?? $post1->cover)->getUrl('card')); ?>" alt="<?php echo e(tr($post1->title)); ?>">
                  </div>
                <?php else: ?>
                  <div class="innovation-card-img innovation-card-img--placeholder">
                    <img src="<?php echo e(asset('img/smbplus/hinh-nay-600x360-1.png')); ?>" alt="<?php echo e(tr($post1->title)); ?>">
                  </div>
                <?php endif; ?>
                <div class="innovation-card-body">
                  <h4><?php echo e(tr($post1->title)); ?></h4>
                  <?php if($post1->excerpt): ?><p><?php echo e(Str::limit(tr($post1->excerpt), 80)); ?></p><?php endif; ?>
                </div>
              </a>
            </article>
          </div>
        <?php else: ?>
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.1s">
            <article class="innovation-card">
              <div class="innovation-card-img">
                <img src="<?php echo e(asset('img/smbplus/hinh-nay-600x360-1.png')); ?>" alt="<?php echo e(__('frontend.news_placeholder_1')); ?>">
              </div>
              <div class="innovation-card-body">
                <h4><?php echo e(__('frontend.news_placeholder_1')); ?></h4>
                <p><?php echo e(__('frontend.news_placeholder_1_sub')); ?></p>
              </div>
            </article>
          </div>
        <?php endif; ?>

        <?php if($posts->count() >= 2): ?>
          <?php $post2 = $posts->get(1); ?>
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.2s">
            <article class="innovation-card">
              <a href="<?php echo e(route('posts.show', $post2->slug)); ?>" class="d-block">
                <?php if($post2->thumbnail || $post2->cover): ?>
                  <div class="innovation-card-img">
                    <img src="<?php echo e(($post2->thumbnail ?? $post2->cover)->getUrl('card')); ?>" alt="<?php echo e(tr($post2->title)); ?>">
                  </div>
                <?php else: ?>
                  <div class="innovation-card-img">
                    <img src="<?php echo e(asset('img/smbplus/maxresdefault.jpg')); ?>" alt="<?php echo e(tr($post2->title)); ?>">
                  </div>
                <?php endif; ?>
                <div class="innovation-card-body">
                  <h4><?php echo e(tr($post2->title)); ?></h4>
                  <?php if($post2->excerpt): ?><p><?php echo e(Str::limit(tr($post2->excerpt), 80)); ?></p><?php endif; ?>
                </div>
              </a>
            </article>
          </div>
        <?php else: ?>
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.2s">
            <article class="innovation-card">
              <div class="innovation-card-img">
                <img src="<?php echo e(asset('img/smbplus/maxresdefault.jpg')); ?>" alt="<?php echo e(__('frontend.news_placeholder_2')); ?>">
              </div>
              <div class="innovation-card-body">
                <h4><?php echo e(__('frontend.news_placeholder_2')); ?></h4>
                <p><?php echo e(__('frontend.news_placeholder_2_sub')); ?></p>
              </div>
            </article>
          </div>
        <?php endif; ?>

        <?php if($posts->count() >= 3): ?>
          <?php $post3 = $posts->get(2); ?>
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.3s">
            <article class="innovation-card">
              <a href="<?php echo e(route('posts.show', $post3->slug)); ?>" class="d-block">
                <?php if($post3->thumbnail || $post3->cover): ?>
                  <div class="innovation-card-img">
                    <img src="<?php echo e(($post3->thumbnail ?? $post3->cover)->getUrl('card')); ?>" alt="<?php echo e(tr($post3->title)); ?>">
                  </div>
                <?php else: ?>
                  <div class="innovation-card-img">
                    <img src="<?php echo e(asset('img/smbplus/8312973a3bf2878f7a1b2c9378420a09dfaf08d9f3b51e7051a1f7b9e328.jpeg')); ?>" alt="<?php echo e(tr($post3->title)); ?>">
                  </div>
                <?php endif; ?>
                <div class="innovation-card-body">
                  <h4><?php echo e(tr($post3->title)); ?></h4>
                  <?php if($post3->excerpt): ?><p><?php echo e(Str::limit(tr($post3->excerpt), 80)); ?></p><?php endif; ?>
                </div>
              </a>
            </article>
          </div>
        <?php else: ?>
          <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.3s">
            <article class="innovation-card">
              <div class="innovation-card-img">
                <img src="<?php echo e(asset('img/smbplus/8312973a3bf2878f7a1b2c9378420a09dfaf08d9f3b51e7051a1f7b9e328.jpeg')); ?>" alt="<?php echo e(__('frontend.news_placeholder_3')); ?>">
              </div>
              <div class="innovation-card-body">
                <h4><?php echo e(__('frontend.news_placeholder_3')); ?></h4>
                <p><?php echo e(__('frontend.news_placeholder_3_sub')); ?></p>
              </div>
            </article>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </section>
  

  
  <section id="cta" class="section-cta wow fadeIn">
    <div class="container text-center">
      <p class="cta-eyebrow"><?php echo e(tr($settings['cta_eyebrow'] ?? '') ?: __('frontend.start_now')); ?></p>
      <h2 class="cta-title"><?php echo e(tr($settings['cta_title'] ?? '') ?: __('frontend.start_now')); ?></h2>
      <p class="cta-sub">
        <?php echo e(tr($settings['cta_subtitle'] ?? '') ?: __('frontend.seo_default_desc')); ?>

      </p>
      <div class="cta-actions">
        <a href="#contact" class="btn-cta-primary scrollto"><?php echo e(__('frontend.free_consultation')); ?></a>
        <a href="<?php echo e(route('event')); ?>" class="btn-cta-secondary"><?php echo e(__('frontend.view_event')); ?></a>
      </div>
    </div>
  </section>
  

  
  <?php echo $__env->make('sections.contact', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/home.blade.php ENDPATH**/ ?>