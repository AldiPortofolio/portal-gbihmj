<div class="form-md-checkboxes <?php echo e(isset($form_class) ? $form_class : ''); ?>">
	<div class="md-checkbox-inline">
		<?php if(isset($label)): ?>
			<label><?php echo e($label); ?></label>
		<?php endif; ?>
		<div class="clearfix"></div>
		<?php if(count($data) > 0): ?>
			<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d => $q): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
				<div class="md-checkbox" <?php $rnd = str_random(10) ?>>
					<input type="checkbox" id="checkbox_form_<?php echo e($rnd); ?>" class="md-check <?php echo e(isset($class) ? $class : ''); ?>" name="<?php echo e($name); ?>[]" <?php echo e(isset($value) ? (in_array($d,$value) ? 'checked' : '') : ''); ?> value="<?php echo e($d); ?>" <?php echo e(isset($attribute) ? $attribute : ''); ?>> 
					<label for="checkbox_form_<?php echo e($rnd); ?>">
						<?php echo e($q); ?>

						<span></span>
				        <span class="check"></span>
				        <span class="box"></span>
					</label>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
		<?php endif; ?>
		<div class="clearfix"></div>
		<small><?php echo e(isset($note) ? $note : ''); ?></small>
	</div>
</div>
