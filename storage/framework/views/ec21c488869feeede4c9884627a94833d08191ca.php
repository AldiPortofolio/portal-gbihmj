<?php if(isset($form_class)): ?>
<div class = "<?php echo e(isset($form_class) ? $form_class : ''); ?>">
<?php endif; ?>
	<div class="form-group form-md-line-input" <?php echo e($rnd = str_random(3)); ?>>
		<input type="<?php echo e(isset($type) ? $type : 'text'); ?>" id="form_floating_<?php echo e($rnd); ?>" class="form-control <?php echo e(isset($class) ? $class : ''); ?>" name="<?php echo e(isset($name) ? $name : ''); ?>" value="<?php echo e(isset($value) ? $value : ''); ?>" <?php echo e(isset($attribute) ? $attribute : ''); ?> placeholder="<?php echo e(isset($placeholder) ? $placeholder : $label); ?>">
		<label for="form_floating_<?php echo e($rnd); ?>"><?php echo e(isset($label) ? $label : ''); ?> <span class="required" aria-required="true"><?php echo e(isset($required) ? ($required == 'y' ? '*' : '') : ''); ?></span></label>
		<small><?php echo isset($note) ? $note : ''; ?></small>
	</div>
<?php if(isset($form_class)): ?>
</div>
	<?php if(in_array('pad-right',explode(' ',$form_class))): ?>
		<div class="clearfix"></div>
	<?php endif; ?>
<?php endif; ?>
