<div class="form-group form-md-line-input <?php echo e(isset($form_class) ? $form_class : ''); ?>">
	<label><?php echo e(isset($label) ? $label : ''); ?> <span class="required" aria-required="true"><?php echo e(isset($required) ? ($required == 'y' ? '*' : '') : '*'); ?></span></label><br/>
	<label class="btn green input-file-label-<?php echo e($name); ?>">
		<input type="<?php echo e(isset($type) ? $type : 'file'); ?>" class="form-control <?php echo e(isset($class) ? $class : ''); ?> <?php echo e(isset($upload_type) ? $upload_type : ''); ?>" name="<?php echo e(isset($name) ? $name : ''); ?>" <?php echo e(isset($attribute) ? $attribute : ''); ?> <?php echo e(isset($crop) && $crop == 'y' ? 'data-crop=y' : ''); ?>> <?php echo e(trans('general.choose_file')); ?>

		<?php if(isset($crop) && $crop == 'y'): ?>
			<input type="hidden" name="<?php echo e($name); ?>_crop">
		<?php endif; ?>
	</label>
	<?php if($upload_type == 'single-image'): ?>
		<button type="button" class="btn red-mint remove-single-image" data-id="<?php echo e($upload_type); ?>" data-name="<?php echo e($name); ?>"><?php echo e(trans('general.remove')); ?></button>
		<input type="hidden" name="remove-<?php echo e($upload_type); ?>-<?php echo e($name); ?>" value="n">
	<?php endif; ?>
	<br>
	<small><?php echo e(isset($note) ? $note : ''); ?></small>
</div>
<?php if(isset($crop) && $crop == 'y'): ?>
	<?php if($upload_type == 'single-image'): ?>
		<div class="modal fade bs-modal-lg" id="crop-modal-<?php echo e($name); ?>" tabindex="-1" role="dialog" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		          <h4 class="modal-title">Crop Image</h4>
		        </div>
		        <div class="modal-body">
		          <center>
		            <div class="waiting-crop"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
		            <div class="container-crop container-crop-<?php echo e($name); ?> hidden">
		              <img class="img-responsive image-crop-data-<?php echo e($name); ?>" alt="Picture">
		            </div>
		          </center>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn dark btn-outline cancel-crop" data-name="<?php echo e($name); ?>">cancel</button>
		          <button type="button" class="btn green save-crop" data-name="<?php echo e($name); ?>">Save changes</button>
		        </div>
		    </div>
		  </div>
		</div>
	<?php else: ?>
		<div class="modal fade bs-modal-lg" id="crop-modal-<?php echo e($name); ?>" tabindex="-1" role="dialog" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		          <h4 class="modal-title">Crop Image</h4>
		        </div>
		        <div class="modal-body">
		          <center>
		            <div class="waiting-crop"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
		            <div class="container-crop container-crop-<?php echo e($name); ?> hidden">
		              <img class="img-responsive image-crop-data-<?php echo e($name); ?>" alt="Picture">
		            </div>
		          </center>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn dark btn-outline cancel-multiple-crop" data-name="<?php echo e($name); ?>" >cancel</button>
		          <button type="button" class="btn green save-multiple-crop" data-name="<?php echo e($name); ?>"  <?php echo e(isset($attribute) ? $attribute : ''); ?> <?php echo e(isset($crop) && $crop == 'y' ? 'data-crop=y' : ''); ?>>Save changes</button>
		        </div>
		    </div>
		  </div>
		</div>
	<?php endif; ?>
<?php endif; ?>
<?php if($upload_type == 'single-image'): ?>
	<div class="form-group <?php echo e($upload_type); ?>-<?php echo e($name); ?> <?php echo e(isset($form_class) ? $form_class : ''); ?>">
		<?php if(isset($value) && $value != ''): ?>
			<img src="<?php echo e(asset($file_opt['path'].$value)); ?>" class="img-responsive thumbnail single-image-thumbnail">
		<?php else: ?>
			<img src="<?php echo e(asset('components/both/images/web/none.png')); ?>" class="img-responsive thumbnail single-image-thumbnail">
		<?php endif; ?>
	</div>
	<div class="clearfix"></div>
<?php endif; ?>
<?php if($upload_type == 'multiple-image'): ?>
	<table class="table table-bordered <?php echo e($upload_type); ?>-<?php echo e($name); ?> <?php echo e(isset($form_class) ? $form_class : ''); ?>">
		<th class="info">Image</th>
		<th class="info">Thumbnail</th>
		<th class="info">Status</th>
		<th class="info">Action</th>
		<tbody class="sortable tbody-<?php echo e($name); ?>">
		<?php if($value != ''): ?>
			<?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
				<tr class='multiple-images'>
					<td><img src="<?php echo e(asset($file_opt['path'].$v->$name)); ?>" width="100"><input type="hidden" name="<?php echo e($name); ?>_hidden_data[]" value="<?php echo e($v->id); ?>"></td>
					<td>
						<div class="form-group form-md-radios">
							<div class="md-radio-inline">
								<div class="md-radio"<?php echo e($rnd = str_random(5)); ?>>
									<input type="radio" class="md-radiobtn" id="radio_<?php echo e($rnd); ?>" name="<?php echo e($name); ?>_status_primary" <?php echo e($v->status_primary == 'y' ? 'checked' : ''); ?> value="<?php echo e($v->id); ?>">
									<label for="radio_<?php echo e($rnd); ?>">
										<span></span>
				                   		<span class="check"></span>
				                    	<span class="box"></span>
									</label>
								</div>
							</div>
						</div>							
					</td>
					<td>
						<div class="form-md-checkboxes <?php echo e(isset($form_class) ? $form_class : ''); ?>">
							<div class="md-checkbox-inline">
								<div class="md-checkbox" <?php $rnd = str_random(10) ?>>
									<input type="checkbox" id="checkbox_form_<?php echo e($rnd); ?>" class="md-check" name="<?php echo e($name); ?>_status[<?php echo e($v->id); ?>]" <?php echo e($v->status == 'y' ? 'checked' : ''); ?> value="y"> 
									<label for="checkbox_form_<?php echo e($rnd); ?>">
										<span></span>
								        <span class="check"></span>
								        <span class="box"></span>
									</label>
								</div>
							</div>
						</div>
					</td>
					<td><button type='button' class='btn btn-primary remove-multiple-image' data-id='<?php echo e($v->id); ?>' data-name='<?php echo e($name); ?>' <?php echo e(isset($attribute) ? $attribute : ''); ?>><i class='fa fa-trash'></i></button></td></tr>
				</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
		<?php endif; ?>
		</tbody>
	</table>
	<div class="clearfix"></div>
<?php endif; ?>