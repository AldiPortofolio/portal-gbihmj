<?php $__env->startSection('content'); ?>
<div id="content">
	<div id="content1">
    <?php echo $msg; ?>

	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('api.mail.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>