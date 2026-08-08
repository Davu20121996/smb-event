
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <?php echo e(trans('global.edit')); ?> <?php echo e(trans('cruds.venue.title_singular')); ?>

    </div>

    <div class="card-body">
        <form action="<?php echo e(route("admin.venues.update", [$venue->id])); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <?php echo $__env->make('admin.partials.multilang_hint', ['mlFields' => ['name', 'address', 'description'], 'isCreate' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="form-group <?php echo e($errors->has('name') ? 'has-error' : ''); ?>">
                <label for="name"><?php echo e(trans('cruds.venue.fields.name')); ?>*</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name', isset($venue) ? $venue->name : '')); ?>" required>
                <?php if($errors->has('name')): ?>
                    <p class="help-block">
                        <?php echo e($errors->first('name')); ?>

                    </p>
                <?php endif; ?>
                <p class="helper-block">
                    <?php echo e(trans('cruds.venue.fields.name_helper')); ?>

                </p>
            </div>
            <div class="form-group <?php echo e($errors->has('photos') ? 'has-error' : ''); ?>">
                <label for="photos"><?php echo e(trans('cruds.venue.fields.photos')); ?></label>
                <div class="needsclick dropzone" id="photos-dropzone">

                </div>
                <?php if($errors->has('photos')): ?>
                    <p class="help-block">
                        <?php echo e($errors->first('photos')); ?>

                    </p>
                <?php endif; ?>
                <p class="helper-block">
                    <?php echo e(trans('cruds.venue.fields.photos_helper')); ?>

                </p>
            </div>
            <div class="form-group <?php echo e($errors->has('address') ? 'has-error' : ''); ?>">
                <label for="address"><?php echo e(trans('cruds.venue.fields.address')); ?>*</label>
                <input type="text" id="address" name="address" class="form-control" value="<?php echo e(old('address', isset($venue) ? $venue->address : '')); ?>" required>
                <?php if($errors->has('address')): ?>
                    <p class="help-block">
                        <?php echo e($errors->first('address')); ?>

                    </p>
                <?php endif; ?>
                <p class="helper-block">
                    <?php echo e(trans('cruds.venue.fields.address_helper')); ?>

                </p>
            </div>
            <div class="form-group <?php echo e($errors->has('latitude') ? 'has-error' : ''); ?>">
                <label for="latitude"><?php echo e(trans('cruds.venue.fields.latitude')); ?></label>
                <input type="text" id="latitude" name="latitude" class="form-control" value="<?php echo e(old('latitude', isset($venue) ? $venue->latitude : '')); ?>">
                <?php if($errors->has('latitude')): ?>
                    <p class="help-block">
                        <?php echo e($errors->first('latitude')); ?>

                    </p>
                <?php endif; ?>
                <p class="helper-block">
                    <?php echo e(trans('cruds.venue.fields.latitude_helper')); ?>

                </p>
            </div>
            <div class="form-group <?php echo e($errors->has('longitude') ? 'has-error' : ''); ?>">
                <label for="longitude"><?php echo e(trans('cruds.venue.fields.longitude')); ?></label>
                <input type="text" id="longitude" name="longitude" class="form-control" value="<?php echo e(old('longitude', isset($venue) ? $venue->longitude : '')); ?>">
                <?php if($errors->has('longitude')): ?>
                    <p class="help-block">
                        <?php echo e($errors->first('longitude')); ?>

                    </p>
                <?php endif; ?>
                <p class="helper-block">
                    <?php echo e(trans('cruds.venue.fields.longitude_helper')); ?>

                </p>
            </div>
            <div class="form-group <?php echo e($errors->has('google_maps_url') ? 'has-error' : ''); ?>">
                <label for="google_maps_url"><?php echo e(trans('cruds.venue.fields.google_maps_url')); ?></label>
                <input type="text" id="google_maps_url" name="google_maps_url" class="form-control" value="<?php echo e(old('google_maps_url', isset($venue) ? $venue->google_maps_url : '')); ?>" placeholder="https://maps.google.com/?q=...">
                <?php if($errors->has('google_maps_url')): ?>
                    <p class="help-block">
                        <?php echo e($errors->first('google_maps_url')); ?>

                    </p>
                <?php endif; ?>
                <p class="helper-block">
                    <?php echo e(trans('cruds.venue.fields.google_maps_url_helper')); ?>

                </p>
            </div>
            <div class="form-group <?php echo e($errors->has('description') ? 'has-error' : ''); ?>">
                <label for="description"><?php echo e(trans('cruds.venue.fields.description')); ?></label>
                <textarea id="description" name="description" class="form-control "><?php echo e(old('description', isset($venue) ? $venue->description : '')); ?></textarea>
                <?php if($errors->has('description')): ?>
                    <p class="help-block">
                        <?php echo e($errors->first('description')); ?>

                    </p>
                <?php endif; ?>
                <p class="helper-block">
                    <?php echo e(trans('cruds.venue.fields.description_helper')); ?>

                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="<?php echo e(trans('global.save')); ?>">
            </div>
        </form>


    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    var uploadedPhotosMap = {}
Dropzone.options.photosDropzone = {
    url: '<?php echo e(route('admin.venues.storeMedia')); ?>',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="photos[]" value="' + response.name + '">')
      uploadedPhotosMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedPhotosMap[file.name]
      }
      $('form').find('input[name="photos[]"][value="' + name + '"]').remove()
    },
    init: function () {
<?php if(isset($venue) && $venue->photos): ?>
      var files =
        <?php echo json_encode($venue->photos); ?>

          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="photos[]" value="' + file.file_name + '">')
        }
<?php endif; ?>
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\laravel\EMS-smb-v3\EMS-smb\resources\views/admin/venues/edit.blade.php ENDPATH**/ ?>