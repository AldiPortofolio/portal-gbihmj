
<?php $__env->startSection('content'); ?>
<div class="portlet light bordered">
  <div class="portlet-title">
      <div class="caption font-green portlet-container">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> <?php echo e($title); ?></span>
        <div class="head-button">
          <?php echo $head_button; ?>

        </div>
      </div>
  </div>
  <div class="portlet-body form">
    <?php echo $content; ?>

  </div>
</div>
<?php if(isset($scripts)): ?>
  <?php $__env->startPush('custom_scripts'); ?>
    <script>
      $(document).ready(function(){
        <?php echo $scripts; ?>

      });
    </script>
  <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php if(isset($current_path)): ?>
  <?php if($current_path == 'user/create'): ?>
    <?php $__env->startPush('scripts'); ?>
      <script>
        console.log('test');
      </script>
    <?php $__env->stopPush(); ?>
  <?php endif; ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($view_path.'.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>