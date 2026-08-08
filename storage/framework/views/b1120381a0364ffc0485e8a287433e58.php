<?php
    $paramName = [
        'speakers' => 'speaker',
        'schedules' => 'schedule',
        'key-benefits' => 'key_benefit',
        'venues' => 'venue',
        'hotels' => 'hotel',
        'galleries' => 'gallery',
        'sponsors' => 'sponsor',
        'faqs' => 'faq',
        'amenities' => 'amenity',
        'prices' => 'price',
    ][$module];

    $routeBase = 'admin.' . $module;
?>

<div class="crud-panel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0"><?php echo e($title); ?></h5>
        <a href="<?php echo e(route($routeBase . '.create', ['event_id' => $event->id])); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm <?php echo e($title); ?></a>
    </div>

    <?php if(count($items) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th><?php echo e($label); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <th style="width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td>
                                <?php if(in_array($key, ['photo', 'logo'])): ?>
                                    <?php if($item->$key): ?>
                                        <img src="<?php echo e($item->$key->thumbnail); ?>" alt="" style="height: 40px;">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                <?php elseif($key === 'icon' && method_exists($item, 'getFirstMediaUrl') && $item->getFirstMediaUrl('icon')): ?>
                                    <img src="<?php echo e($item->getFirstMediaUrl('icon', 'thumb')); ?>" alt="" style="height: 40px;">
                                <?php elseif($key === 'speaker_name'): ?>
                                    <?php echo e($item->speaker->name ?? '-'); ?>

                                <?php elseif($key === 'photos_count'): ?>
                                    <?php echo e($item->photos ? count($item->photos) : 0); ?>

                                <?php elseif($key === 'amenities'): ?>
                                    <?php echo e($item->amenities ? $item->amenities->pluck('name')->join(', ') : '-'); ?>

                                <?php elseif($key === 'price'): ?>
                                    <?php echo e(number_format($item->price, 0, ',', '.')); ?>

                                <?php elseif($key === 'answer' || $key === 'description' || $key === 'full_description' || $key === 'desc'): ?>
                                    <?php echo e(\Illuminate\Support\Str::limit(strip_tags($item->$key ?? ''), 80)); ?>

                                <?php else: ?>
                                    <?php echo e($item->$key ?? '-'); ?>

                                <?php endif; ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <td>
                            <a href="<?php echo e(route($routeBase . '.edit', [$paramName => $item->id, 'event_id' => $event->id])); ?>" class="btn btn-xs btn-info" title="Sửa"><i class="fa fa-edit"></i> Sửa</a>
                            <form method="POST" action="<?php echo e(route($routeBase . '.destroy', [$paramName => $item->id])); ?>" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <input type="hidden" name="event_id" value="<?php echo e($event->id); ?>">
                                <button type="submit" class="btn btn-xs btn-danger" title="Xóa"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">Chưa có <?php echo e($title); ?> nào.</p>
    <?php endif; ?>
</div><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/admin/events/_child.blade.php ENDPATH**/ ?>