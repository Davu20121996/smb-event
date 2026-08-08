
<?php $__env->startSection('content'); ?>
<div class="login-box">
    <div class="login-logo">
        <a href="#">
            <?php echo e(trans('panel.site_title')); ?>

        </a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?php if(\Session::has('message')): ?>
                <p class="alert alert-info">
                    <?php echo e(\Session::get('message')); ?>

                </p>
            <?php endif; ?>
            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <input type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" required autofocus placeholder="<?php echo e(trans('global.login_email')); ?>" name="email" value="<?php echo e(old('email', null)); ?>">
                    <?php if($errors->has('email')): ?>
                        <div class="invalid-feedback">
                            <?php echo e($errors->first('email')); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" required placeholder="<?php echo e(trans('global.login_password')); ?>" name="password">
                    <?php if($errors->has('password')): ?>
                        <div class="invalid-feedback">
                            <?php echo e($errors->first('password')); ?>

                        </div>
                    <?php endif; ?>
                </div>


                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember"><?php echo e(trans('global.remember_me')); ?></label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo e(trans('global.login')); ?></button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>



            <p class="mb-1">
                <a class="" href="<?php echo e(route('password.request')); ?>">
                    <?php echo e(trans('global.forgot_password')); ?>

                </a>
            </p>
            <p class="mb-0">

            </p>
            <p class="mb-1">

            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/auth/login.blade.php ENDPATH**/ ?>