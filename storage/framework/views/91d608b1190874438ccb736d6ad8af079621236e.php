<?php if(isset($form_class)): ?>
<div class="<?php echo e(isset($form_class) ? $form_class : ''); ?>">
<?php endif; ?>	
	<div class="form-group form-md-line-input">
		<label><?php echo e(isset($label) ? $label : ''); ?> <span class="required" aria-required="true"><?php echo e(isset($required) ? ($required == 'y' ? '*' : '') : '*'); ?></span></label>
		<select id="<?php echo e(isset($id) ? $id : ''); ?>" class="form-control <?php echo e(isset($class) ? $class : ''); ?>" name="<?php echo e(isset($name) ? $name : ''); ?>" <?php echo e(isset($attribute) ? $attribute : ''); ?> onchange="<?php echo e(isset($onchange) ? $onchange : ''); ?>">
			<option value="" <?php echo e(isset($value) ? ($value == '' ? 'selected' : '') : ''); ?>>--Select <?php echo e($label); ?>--</option>
			<?php if(count($data) > 0): ?>
				<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d => $q): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
					<option value=<?php echo e($d); ?> <?php echo e($value == $d ? 'selected' : ''); ?>><?php echo e($q); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
			<?php endif; ?>
		<select>
		<div class="form-control-focus"> </div>
		<small><?php echo e(isset($note) ? $note : ''); ?></small>
	</div>
<?php if(isset($form_class)): ?>
</div>
<?php endif; ?>