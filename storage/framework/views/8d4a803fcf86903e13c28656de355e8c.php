<?php $__env->startSection('styles'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('styles'); ?>
<link href="<?php echo e(asset('lib/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" />
<style>
.stat-card {
  position: relative;
  overflow: hidden;
  border-radius: var(--radius-lg);
  border: 1px solid var(--hairline);
  background: var(--canvas);
  box-shadow: var(--shadow-soft);
  padding: 20px 22px;
  height: 100%;
}
.stat-card .stat-icon {
  position: absolute;
  right: 14px;
  top: 14px;
  width: 44px;
  height: 44px;
  border-radius: var(--radius-full);
  background: rgba(0, 117, 74, 0.10);
  color: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
}
.stat-card .stat-label {
  font-size: 13px;
  color: var(--ink-muted);
  margin-bottom: 6px;
  letter-spacing: 0.2px;
}
.stat-card .stat-value {
  font-size: 30px;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.5px;
}
.stat-card .stat-sub {
  margin-top: 6px;
  font-size: 12.5px;
  color: var(--ink-secondary);
}
.stat-card .stat-sub a {
  font-weight: 600;
}
.mini-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: var(--radius-full);
  background: var(--canvas);
  border: 1px solid var(--hairline);
  font-size: 13px;
  color: var(--ink-secondary);
  box-shadow: var(--shadow-soft);
}
.mini-pill strong {
  color: var(--ink);
}
.mini-pill .dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--primary);
  flex-shrink: 0;
}
.landing-table td .badge-success,
.landing-table td .badge-secondary {
  font-weight: 600;
}
.recent-empty {
  padding: 28px;
  text-align: center;
  color: var(--ink-muted);
  font-size: 13.5px;
}
.recent-list .recent-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 2px;
  border-bottom: 1px solid var(--hairline);
}
.recent-list .recent-item:last-child {
  border-bottom: none;
}
.recent-list .recent-avatar {
  width: 36px;
  height: 36px;
  border-radius: var(--radius-full);
  background: rgba(0, 117, 74, 0.10);
  color: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 15px;
  font-weight: 700;
  letter-spacing: 0.5px;
}
.recent-list .recent-name {
  font-weight: 600;
  color: var(--ink);
  font-size: 13.5px;
}
.recent-list .recent-meta {
  font-size: 12px;
  color: var(--ink-muted);
}
.recent-list .recent-time {
  margin-left: auto;
  font-size: 12px;
  color: var(--ink-faint);
  white-space: nowrap;
}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <h5 class="mb-3" style="color: var(--ink); letter-spacing: -0.3px;">Tổng quan hệ thống</h5>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa fa-ticket"></i></div>
            <div class="stat-label">Lượt đăng ký sự kiện</div>
            <div class="stat-value"><?php echo e(number_format($totalEventRegistrations)); ?></div>
            <div class="stat-sub">
                <?php if($activeEvent): ?>
                    Event active: <a href="<?php echo e(route('admin.events.show', $activeEvent->id)); ?>"><?php echo e($activeEvent->name); ?></a>
                <?php else: ?>
                    Chưa có event active
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa fa-leanpub"></i></div>
            <div class="stat-label">Lead từ Landing Page</div>
            <div class="stat-value"><?php echo e(number_format($totalLandingLeads)); ?></div>
            <div class="stat-sub">Người vào trang &amp; để lại thông tin</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa fa-envelope-o"></i></div>
            <div class="stat-label">Liên hệ từ trang chủ</div>
            <div class="stat-value"><?php echo e(number_format($totalHomeMessages)); ?></div>
            <div class="stat-sub">Form liên hệ tổng đài</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa fa-bullhorn"></i></div>
            <div class="stat-label">Event đang active</div>
            <div class="stat-value" style="font-size:20px; line-height:1.4; padding-top:6px;">
                <?php if($activeEvent): ?>
                    <?php echo e($activeEvent->name); ?>

                <?php else: ?>
                    Không có
                <?php endif; ?>
            </div>
            <div class="stat-sub">
                <?php if($activeEvent): ?>
                    <?php echo e($activeEvent->start_date ? date('d/m/Y', strtotime($activeEvent->start_date)) : '—'); ?> &rarr; <?php echo e($activeEvent->end_date ? date('d/m/Y', strtotime($activeEvent->end_date)) : '—'); ?>

                <?php else: ?>
                    <a href="<?php echo e(route('admin.events.index')); ?>">Vào Quản lý event</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-6 mb-3">
        <h6 class="mb-2" style="color: var(--ink-muted); font-weight:600;">Thống kê Landing Page</h6>
        <div class="d-flex flex-wrap" style="gap: 8px;">
            <span class="mini-pill"><span class="dot" style="background: var(--ink-faint);"></span> Tổng landing page: <strong><?php echo e($landingStats['total']); ?></strong></span>
            <span class="mini-pill"><span class="dot" style="background: var(--primary);"></span> Đang publish: <strong><?php echo e($landingStats['published']); ?></strong></span>
            <span class="mini-pill"><span class="dot" style="background: var(--ink-faint);"></span> Chưa publish: <strong><?php echo e($landingStats['unpublished']); ?></strong></span>
            <span class="mini-pill"><span class="dot" style="background: #d97706;"></span> Tổng lead: <strong><?php echo e(number_format($landingStats['leads'])); ?></strong></span>
        </div>
    </div>
    <div class="col-lg-6 mb-3">
        <h6 class="mb-2" style="color: var(--ink-muted); font-weight:600;">Thống kê Event</h6>
        <div class="d-flex flex-wrap" style="gap: 8px;">
            <span class="mini-pill"><span class="dot" style="background: var(--ink-faint);"></span> Tổng event: <strong><?php echo e($eventStats['total']); ?></strong></span>
            <span class="mini-pill"><span class="dot" style="background: var(--primary);"></span> Đang active: <strong><?php echo e($eventStats['active']); ?></strong></span>
            <span class="mini-pill"><span class="dot" style="background: #d97706;"></span> Sắp diễn ra: <strong><?php echo e($eventStats['upcoming']); ?></strong></span>
            <span class="mini-pill"><span class="dot" style="background: var(--ink-faint);"></span> Đã kết thúc: <strong><?php echo e($eventStats['past']); ?></strong></span>
            <span class="mini-pill"><span class="dot" style="background: var(--primary);"></span> Tổng đăng ký: <strong><?php echo e(number_format($eventStats['registrations'])); ?></strong></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                Danh sách Event
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover landing-table mb-0" style="font-size: 13.5px;">
                        <thead>
                            <tr>
                                <th>Tên event</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Diễn giả</th>
                                <th class="text-center">Đăng ký</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('admin.events.show', $ev->id)); ?>" style="font-weight:600;"><?php echo e($ev->name); ?></a>
                                    </td>
                                    <td style="color: var(--ink-muted); font-size:12.5px;">
                                        <?php echo e($ev->start_date ? date('d/m/Y', strtotime($ev->start_date)) : '—'); ?> &rarr; <?php echo e($ev->end_date ? date('d/m/Y', strtotime($ev->end_date)) : '—'); ?>

                                    </td>
                                    <td>
                                        <?php if($ev->is_active): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo e($ev->speakers_count); ?></td>
                                    <td class="text-center">
                                        <strong style="color: var(--primary);"><?php echo e(number_format($ev->contact_messages_count)); ?></strong>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="5" class="recent-empty">Chưa có event nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                Danh sách Landing Page
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover landing-table mb-0" style="font-size: 13.5px;">
                        <thead>
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Slug</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Lead</th>
                                <th class="text-center">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $landingPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('admin.landing-pages.show', $lp->id)); ?>" style="font-weight:600;"><?php echo e($lp->title); ?></a>
                                        <?php if($lp->crm_tag): ?>
                                            <div style="font-size:11.5px; color:var(--ink-muted);">tag: <?php echo e($lp->crm_tag); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><code style="font-size:12px;"><?php echo e($lp->slug); ?></code></td>
                                    <td>
                                        <?php if($lp->is_published): ?>
                                            <span class="badge badge-success">Published</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Draft</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <strong style="color: var(--primary);"><?php echo e(number_format($lp->leads_count)); ?></strong>
                                    </td>
                                    <td class="text-center" style="color: var(--ink-muted); font-size:12.5px;">
                                        <?php echo e(optional($lp->created_at)->format('d/m/Y')); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="5" class="recent-empty">Chưa có landing page nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                Đăng ký sự kiện gần đây
            </div>
            <div class="card-body p-3">
                <div class="recent-list">
                    <?php $__empty_1 = true; $__currentLoopData = $recentRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="recent-item">
                            <div class="recent-avatar"><?php echo e(mb_strtoupper(mb_substr($reg->name, 0, 1))); ?></div>
                            <div>
                                <div class="recent-name"><?php echo e($reg->name); ?></div>
                                <div class="recent-meta"><?php echo e($reg->email); ?> &middot; <?php echo e($reg->event->name ?? 'Event'); ?></div>
                            </div>
                            <div class="recent-time"><?php echo e($reg->created_at->diffForHumans()); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="recent-empty">Chưa có đăng ký nào.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                Lead Landing Page gần đây
            </div>
            <div class="card-body p-3">
                <div class="recent-list">
                    <?php $__empty_1 = true; $__currentLoopData = $recentLeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="recent-item">
                            <div class="recent-avatar"><?php echo e(mb_strtoupper(mb_substr($lead->name, 0, 1))); ?></div>
                            <div>
                                <div class="recent-name"><?php echo e($lead->name); ?></div>
                                <div class="recent-meta"><?php echo e($lead->email); ?> &middot; <?php echo e($lead->landingPage->title ?? 'Landing'); ?></div>
                            </div>
                            <div class="recent-time"><?php echo e($lead->created_at->diffForHumans()); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="recent-empty">Chưa có lead nào.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/admin/home.blade.php ENDPATH**/ ?>