<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(trans('panel.site_title')); ?></title>
    <link href="<?php echo e(asset('vendor/admin/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/adminltev3.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('vendor/admin/fontawesome/css/all.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('vendor/admin/icheck-bootstrap/icheck-bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="<?php echo e(asset('css/design-system.css') . '?v=' . @filemtime(public_path('css/design-system.css'))); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/auth.css') . '?v=' . @filemtime(public_path('css/auth.css'))); ?>" rel="stylesheet" />
    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    <?php echo $__env->yieldContent('content'); ?>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/layouts/app.blade.php ENDPATH**/ ?>