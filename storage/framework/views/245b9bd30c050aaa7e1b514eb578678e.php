<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light"><?php echo e(trans('panel.site_title')); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo e(route("admin.home")); ?>" class="nav-link">
                        <p>
                            <i class="fas fa-fw fa-tachometer-alt">

                            </i>
                            <span><?php echo e(trans('global.dashboard')); ?></span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.help')); ?>" class="nav-link <?php echo e(request()->is('admin/huong-dan-su-dung') ? 'active' : ''); ?>">
                        <p>
                            <i class="fa-fw fas fa-book-open"></i>
                            <span>Hướng dẫn sử dụng</span>
                        </p>
                    </a>
                </li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event_access')): ?>
                    <li class="nav-item">
                        <form action="<?php echo e(route('admin.events.switch')); ?>" method="POST" class="px-3 py-1">
                            <?php echo e(csrf_field()); ?>

                            <div class="form-group mb-1">
                                <label style="color:#c2c7d0; font-size:.8rem; margin-bottom:.25rem; display:block;">
                                    <i class="fa-fw fas fa-calendar-alt"></i>
                                    <?php echo e(trans('cruds.event.title')); ?>

                                </label>
                                <div class="input-group input-group-sm">
                                    <select name="event_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                        <?php $__currentLoopData = \App\Event::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($event->id); ?>" <?php echo e(session('current_event_id', 1) == $event->id ? 'selected' : ''); ?>>
                                                <?php echo e($event->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event_access')): ?>
                    <li class="nav-item has-treeview <?php echo e(request()->is('admin/events*') || request()->is('admin/speakers*') || request()->is('admin/schedules*') || request()->is('admin/key-benefits*') || request()->is('admin/venues*') || request()->is('admin/hotels*') || request()->is('admin/galleries*') || request()->is('admin/sponsors*') || request()->is('admin/faqs*') || request()->is('admin/amenities*') || request()->is('admin/prices*') || request()->is('admin/settings*') ? 'menu-open' : ''); ?>">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw fas fa-calendar-alt">

                            </i>
                            <p>
                                <span><?php echo e(trans('cruds.event.title')); ?></span>
                                <i class="right fa fa-fw fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.events.index")); ?>" class="nav-link <?php echo e(request()->is('admin/events') || request()->is('admin/events/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-calendar-check">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.event.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('speaker_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.speakers.index")); ?>" class="nav-link <?php echo e(request()->is('admin/speakers') || request()->is('admin/speakers/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-users">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.speaker.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('schedule_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.schedules.index")); ?>" class="nav-link <?php echo e(request()->is('admin/schedules') || request()->is('admin/schedules/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw far fa-clock">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.schedule.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('key_benefit_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.key-benefits.index")); ?>" class="nav-link <?php echo e(request()->is('admin/key-benefits') || request()->is('admin/key-benefits/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-star">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.keyBenefit.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('venue_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.venues.index")); ?>" class="nav-link <?php echo e(request()->is('admin/venues') || request()->is('admin/venues/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-map-marker-alt">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.venue.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hotel_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.hotels.index")); ?>" class="nav-link <?php echo e(request()->is('admin/hotels') || request()->is('admin/hotels/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-hotel">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.hotel.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('gallery_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.galleries.index")); ?>" class="nav-link <?php echo e(request()->is('admin/galleries') || request()->is('admin/galleries/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-images">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.gallery.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sponsor_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.sponsors.index")); ?>" class="nav-link <?php echo e(request()->is('admin/sponsors') || request()->is('admin/sponsors/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-hand-holding-usd">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.sponsor.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faq_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.faqs.index")); ?>" class="nav-link <?php echo e(request()->is('admin/faqs') || request()->is('admin/faqs/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-question">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.faq.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('amenity_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.amenities.index")); ?>" class="nav-link <?php echo e(request()->is('admin/amenities') || request()->is('admin/amenities/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-check">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.amenity.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('price_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.prices.index")); ?>" class="nav-link <?php echo e(request()->is('admin/prices') || request()->is('admin/prices/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-money-bill">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.price.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.settings.index")); ?>" class="nav-link <?php echo e(request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-cogs">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.setting.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route("admin.posts.index")); ?>" class="nav-link <?php echo e(request()->is('admin/posts') || request()->is('admin/posts/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-newspaper">

                            </i>
                            <p>
                                <span><?php echo e(trans('cruds.post.title')); ?></span>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route("admin.menus.index")); ?>" class="nav-link <?php echo e(request()->is('admin/menus') || request()->is('admin/menus/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-bars">

                            </i>
                            <p>
                                <span><?php echo e(trans('cruds.menu.title')); ?></span>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('landing_page_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route("admin.landing-pages.index")); ?>" class="nav-link <?php echo e(request()->is('admin/landing-pages') || request()->is('admin/landing-pages/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-file-alt">

                            </i>
                            <p>
                                <span><?php echo e(trans('cruds.landingPage.title')); ?></span>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('company_profile_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route("admin.company-profile.index")); ?>" class="nav-link <?php echo e(request()->is('admin/company-profile') || request()->is('admin/company-profile/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-briefcase">

                            </i>
                            <p>
                                <span>Company Profile</span>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_management_access')): ?>
                    <li class="nav-item has-treeview <?php echo e(request()->is('admin/permissions*') ? 'menu-open' : ''); ?> <?php echo e(request()->is('admin/roles*') ? 'menu-open' : ''); ?> <?php echo e(request()->is('admin/users*') ? 'menu-open' : ''); ?>">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw fas fa-users">

                            </i>
                            <p>
                                <span><?php echo e(trans('cruds.userManagement.title')); ?></span>
                                <i class="right fa fa-fw fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.permissions.index")); ?>" class="nav-link <?php echo e(request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.permission.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.roles.index")); ?>" class="nav-link <?php echo e(request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-briefcase">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.role.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_access')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route("admin.users.index")); ?>" class="nav-link <?php echo e(request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : ''); ?>">
                                        <i class="fa-fw fas fa-user">

                                        </i>
                                        <p>
                                            <span><?php echo e(trans('cruds.user.title')); ?></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?php echo e(route("admin.contacts.index")); ?>" class="nav-link <?php echo e(request()->is('admin/contacts') || request()->is('admin/contacts/*') ? 'active' : ''); ?>">
                        <i class="fa-fw fas fa-envelope">

                        </i>
                        <p>
                            <span>Contact Messages</span>
                        </p>
                    </a>
                </li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('attendee_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route("admin.attendees.index")); ?>" class="nav-link <?php echo e(request()->is('admin/attendees') || request()->is('admin/attendees/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-user-check">

                            </i>
                            <p>
                                <span>Khách đăng ký Event</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route("admin.checkin.index")); ?>" class="nav-link <?php echo e(request()->is('admin/checkin') || request()->is('admin/checkin/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-camera">

                            </i>
                            <p>
                                <span>Quét QR Check-in</span>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route("admin.vouchers.index")); ?>" class="nav-link <?php echo e(request()->is('admin/vouchers') || request()->is('admin/vouchers/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-ticket-alt">

                            </i>
                            <p>
                                <span>Quản lý Voucher</span>
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt">

                            </i>
                            <span><?php echo e(trans('global.logout')); ?></span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/partials/menu.blade.php ENDPATH**/ ?>