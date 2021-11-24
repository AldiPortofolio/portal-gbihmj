
<?php $__env->startPush('css'); ?>
<style type="text/css">
	.btn_check_array{
		/* display: inline-block; */
	}
	.check_result{
		display: none;
		height: 100%;
		margin-bottom: 10px;
		box-shadow: unset;
	}
	.single-audio_wrapper{
    	font-weight: bold;
	}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<form class="showLoading" role="form" method="post" action="<?php echo e(url($path)); ?>/<?php echo e($data->id); ?>" enctype="multipart/form-data">
	 <?php echo e(method_field('PUT')); ?>

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
				<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'title','label' => 'Title','value' => (old('title') ? old('title') : $data->title),'attribute' => 'autofocus required','form_class' => 'col-md-6']); ?>


				<?php echo view($view_path.'.builder.file',['name' => 'image','label' => 'image','value' => $data->image,'type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'y']); ?>

			
			  	<div class="form-group form-md-line-input col-md-12">
					<label>File <span class="required" aria-required="true"></span></label><br>
					<label class="btn green input-file-label-image">
						<input type="file" class="form-control col-md-12 single-audio" name="music_name"> Pilih File
							</label>
							<button type="button" class="btn red-mint remove-single-audio" data-id="single-audio" data-name="music_name">Hapus</button>
						<input type="hidden" name="remove-single-file-music_name" value="n">
						<br>
					<small>Note: File Must mp3</small>
				</div>

				<div class="form-group single-audio_wrapper single-audio-music_name col-md-12"><?php echo e($data->music_name); ?></div>

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