<?php if(isset($form_class)): ?>
	<div class="form-group <?php echo e(isset($form_class) ? $form_class : ''); ?>">
<?php endif; ?>
	<button type="<?php echo e(isset($type) ? $type : 'button'); ?>" class="btn green <?php echo e(isset($class) ? $class : ''); ?> <?php echo e((isset($ask) && $ask == 'y') ? 'ask' : ''); ?>" <?php echo isset($attribute) ? $attribute : ''; ?>> <?php echo isset($label) ? $label : 'Submit'; ?></button>
<?php if(isset($form_class)): ?>
	</div>
<?php endif; ?>	