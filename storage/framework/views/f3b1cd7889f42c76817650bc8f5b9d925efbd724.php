<?php if(isset($form_class)): ?>
	<div class = "<?php echo e(isset($form_class) ? $form_class : ''); ?>">
<?php endif; ?>
	<div class="form-group form-md-line-input" <?php echo e($rnd = str_random(3)); ?>>
		<textarea class="form-control <?php echo e(isset($class) ? $class : ''); ?>" id="form_floating_<?php echo e($rnd); ?>" name="<?php echo e(isset($name) ? $name : ''); ?>" <?php echo e(isset($attribute) ? $attribute : ''); ?> placeholder="<?php echo e($label); ?>"><?php echo e($value); ?></textarea>
		<label for="form_floating_<?php echo e($rnd); ?>"><?php echo e(isset($label) ? $label : ''); ?> <span class="required" aria-required="true"><?php echo e(isset($required) ? ($required == 'y' ? '*' : '') : ''); ?></span></label>
		<?php echo e(isset($note) ? '<span class="help-block">'.$note.'</span>span>' : ''); ?></span>
	</div>
<?php if(isset($form_class)): ?>
	</div>
<?php endif; ?>