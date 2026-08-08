<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("admin.home") }}" class="nav-link">
                        <p>
                            <i class="fas fa-fw fa-tachometer-alt">

                            </i>
                            <span>{{ trans('global.dashboard') }}</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.help') }}" class="nav-link {{ request()->is('admin/huong-dan-su-dung') ? 'active' : '' }}">
                        <p>
                            <i class="fa-fw fas fa-book-open"></i>
                            <span>Hướng dẫn sử dụng</span>
                        </p>
                    </a>
                </li>
                @can('event_access')
                    <li class="nav-item">
                        <form action="{{ route('admin.events.switch') }}" method="POST" class="px-3 py-1">
                            {{ csrf_field() }}
                            <div class="form-group mb-1">
                                <label style="color:#c2c7d0; font-size:.8rem; margin-bottom:.25rem; display:block;">
                                    <i class="fa-fw fas fa-calendar-alt"></i>
                                    {{ trans('cruds.event.title') }}
                                </label>
                                <div class="input-group input-group-sm">
                                    <select name="event_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                        @foreach(\App\Event::all() as $event)
                                            <option value="{{ $event->id }}" {{ session('current_event_id', 1) == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </li>
                @endcan
                @can('event_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/events*') || request()->is('admin/speakers*') || request()->is('admin/schedules*') || request()->is('admin/key-benefits*') || request()->is('admin/venues*') || request()->is('admin/hotels*') || request()->is('admin/galleries*') || request()->is('admin/sponsors*') || request()->is('admin/faqs*') || request()->is('admin/amenities*') || request()->is('admin/prices*') || request()->is('admin/settings*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw fas fa-calendar-alt">

                            </i>
                            <p>
                                <span>{{ trans('cruds.event.title') }}</span>
                                <i class="right fa fa-fw fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('event_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.events.index") }}" class="nav-link {{ request()->is('admin/events') || request()->is('admin/events/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-calendar-check">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.event.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('speaker_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.speakers.index") }}" class="nav-link {{ request()->is('admin/speakers') || request()->is('admin/speakers/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-users">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.speaker.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('schedule_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.schedules.index") }}" class="nav-link {{ request()->is('admin/schedules') || request()->is('admin/schedules/*') ? 'active' : '' }}">
                                        <i class="fa-fw far fa-clock">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.schedule.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('key_benefit_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.key-benefits.index") }}" class="nav-link {{ request()->is('admin/key-benefits') || request()->is('admin/key-benefits/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-star">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.keyBenefit.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('venue_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.venues.index") }}" class="nav-link {{ request()->is('admin/venues') || request()->is('admin/venues/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-map-marker-alt">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.venue.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('hotel_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.hotels.index") }}" class="nav-link {{ request()->is('admin/hotels') || request()->is('admin/hotels/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-hotel">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.hotel.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('gallery_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.galleries.index") }}" class="nav-link {{ request()->is('admin/galleries') || request()->is('admin/galleries/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-images">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.gallery.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('sponsor_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.sponsors.index") }}" class="nav-link {{ request()->is('admin/sponsors') || request()->is('admin/sponsors/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-hand-holding-usd">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.sponsor.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('faq_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.faqs.index") }}" class="nav-link {{ request()->is('admin/faqs') || request()->is('admin/faqs/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-question">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.faq.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('amenity_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.amenities.index") }}" class="nav-link {{ request()->is('admin/amenities') || request()->is('admin/amenities/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-check">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.amenity.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('price_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.prices.index") }}" class="nav-link {{ request()->is('admin/prices') || request()->is('admin/prices/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-money-bill">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.price.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('setting_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.settings.index") }}" class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-cogs">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.setting.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('post_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.posts.index") }}" class="nav-link {{ request()->is('admin/posts') || request()->is('admin/posts/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-newspaper">

                            </i>
                            <p>
                                <span>{{ trans('cruds.post.title') }}</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('menu_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.menus.index") }}" class="nav-link {{ request()->is('admin/menus') || request()->is('admin/menus/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-bars">

                            </i>
                            <p>
                                <span>{{ trans('cruds.menu.title') }}</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('landing_page_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.landing-pages.index") }}" class="nav-link {{ request()->is('admin/landing-pages') || request()->is('admin/landing-pages/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-file-alt">

                            </i>
                            <p>
                                <span>{{ trans('cruds.landingPage.title') }}</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('company_profile_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.company-profile.index") }}" class="nav-link {{ request()->is('admin/company-profile') || request()->is('admin/company-profile/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-briefcase">

                            </i>
                            <p>
                                <span>Company Profile</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw fas fa-users">

                            </i>
                            <p>
                                <span>{{ trans('cruds.userManagement.title') }}</span>
                                <i class="right fa fa-fw fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.permission.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-briefcase">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.role.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                        <i class="fa-fw fas fa-user">

                                        </i>
                                        <p>
                                            <span>{{ trans('cruds.user.title') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ route("admin.contacts.index") }}" class="nav-link {{ request()->is('admin/contacts') || request()->is('admin/contacts/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-envelope">

                        </i>
                        <p>
                            <span>Contact Messages</span>
                        </p>
                    </a>
                </li>
                @can('attendee_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.attendees.index") }}" class="nav-link {{ request()->is('admin/attendees') || request()->is('admin/attendees/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-user-check">

                            </i>
                            <p>
                                <span>Khách đăng ký Event</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route("admin.checkin.index") }}" class="nav-link {{ request()->is('admin/checkin') || request()->is('admin/checkin/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-camera">

                            </i>
                            <p>
                                <span>Quét QR Check-in</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('voucher_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.vouchers.index") }}" class="nav-link {{ request()->is('admin/vouchers') || request()->is('admin/vouchers/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-ticket-alt">

                            </i>
                            <p>
                                <span>Quản lý Voucher</span>
                            </p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt">

                            </i>
                            <span>{{ trans('global.logout') }}</span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
