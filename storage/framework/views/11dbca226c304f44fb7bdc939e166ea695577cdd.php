
<?php $__env->startPush('css'); ?>
<style type="text/css">
	.btn_check_array{
		/* display: inline-block; */
	}
	.check_result{
		/* display: none;
		height: 100%;
		margin-bottom: 10px;
		box-shadow: unset; */
	}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<form role="form" method="post" action="<?php echo e(url($path)); ?>" enctype="multipart/form-data">
	<div class="portlet light bordered">
    	<div class="portlet-title">
			<div class="caption font-green">
				<i class="icon-layers font-green title-icon"></i>
				<span class="caption-subject bold uppercase"> <?php echo e($title); ?></span>
			</div>
			<div class="actions">
				<a href="<?php echo e(url($path)); ?>"><button type="button" class="btn red-mint"><?php echo e(trans('general.back')); ?></button></a>
			</div>
    	</div>
    	<div class="portlet-body form">
      		<?php echo $__env->make('admin.includes.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	  		<div class="row">				
				<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'title','label' => 'Title','value' => (old('title') ? old('title') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'title', 'required' => 'y']); ?>

				
				<?php echo view($view_path.'.builder.textarea',['type' => 'text','name' => 'content','label' => 'Content','value' => (old('content') ? old('content') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'content', 'required' => 'y']); ?>

				

				<?php echo view($view_path.'.builder.file',['name' => 'image','label' => 'Image','value' => '','type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'yield']); ?>


				<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'write_by','label' => 'Write by','value' => (old('write_by') ? old('write_by') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'write_by', 'required' => 'y']); ?>

				
				<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'url','label' => 'URL','value' => (old('url') ? old('url') : ''),'attribute' => 'autofocus','form_class' => 'col-md-12', 'class' => 'url', 'required' => 'n']); ?>


				<div class="col-md-6" id="">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="article_date" class="form-control" name="article_date" value="" readonly="" placeholder="Event Date">
		            <label for="form_floating_Hqd">Article Date <!--<span class="" aria-required="true">*</span>--></label>
		            <small></small>
		          </div>
		        </div>

				<div class="form-group col-md-12" style="">
				  	<div class="md-checkbox">
				  		<label>Status Banner (Jika ingin menampilkan article di banner)</label>
						<input type="checkbox" id="checkbox_form_1" class="md-check status_banner" name="status_banner" value="n" >
						<label for="checkbox_form_1">
							<span></span>
					        <span class="check"></span>
					        <span class="box"></span>
						</label>
					</div>
				</div>

			</div>	
			<?php echo view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green btn_submit','label' => trans('general.submit'),'ask' => 'n']); ?>

		</div>
	</div>
</form>
<?php $__env->startPush('custom_scripts'); ?>
	<script>
		$(document).ready(function(){
			
			$("#article_date").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,

              	onSelect: function(dateText, inst) {

              	}
          	});
			
			$('.status_banner').on('click', function(e){
				if($(".status_banner:checked").length > 0){
					$(this).val('y');
				}else{
					$(this).val('n');
				}
			});
		});
	</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($view_path.'.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>