
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
	.single-audio_wrapper{
    	font-weight: bold;
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
			  	<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'youtube_url','label' => 'Youtube URL','value' => (old('youtube_url') ? old('youtube_url') : ''),'attribute' => 'autofocus required','form_class' => 'col-md-6 youtube_url_wrapper', 'class' => 'youtube_url', 'placeholder' => 'https://www.youtube.com/watch?v=We9fYv6iLWg']); ?>

				
				<div class="col-md-6">
					<div class="btn blue check_url" style="margin-bottom: 20px;">Check URL</div>
				</div>

				<!-- <?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'title','label' => 'Title','value' => (old('title') ? old('title') : ''),'attribute' => 'autofocus required','form_class' => 'col-md-6']); ?>

			
			  	<div class="form-group form-md-line-input col-md-12">
					<label>Image <span class="required" aria-required="true"></span></label><br>
					<input type="hiden" name="image">
					<img src="<?php echo e(asset('components/both/images/web/none.png')); ?>" class="img-responsive thumbnail single-image-thumbnail">
				</div> -->

				<div class="form-group col-md-12">
					<input type='hidden' class='base_url' value=<?php echo e($base_url); ?>>
					<input type="hidden" name="title">
					<div class='title'>Title</div>
					<div class='image'>
						<input type="hidden" name="image">
						<!-- <object data="<?php echo e(asset('components/both/images/web/none.png')); ?>" type="image/png">	 -->
							<img src="" onerror="if (this.src != '<?php echo e(asset('components/both/images/web/none.png')); ?>') this.src = '<?php echo e(asset('components/both/images/web/none.png')); ?>';" class="img-responsive thumbnail single-image-thumbnail" style="margin: 0;'">
				  		<!-- </object> -->
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
			$('.btn_submit').attr('disabled', '');
			$(".check_url").click(function(){
				let url = $('.youtube_url').val();
				let root_url = $('.base_url').val();
				// root_url = root_url[0]+'/'+root_url[1]+'/'+root_url[2]+'/'+root_url[3];		
		    
				var request = $.ajax({
					url: root_url+'/api/get_youtubeVideoData?url_youtube='+url,
					method: "GET",
					data: {},
					dataType: "json"
				});
				
				request.done(function( data ) {
					// let res = JSON.parse(data);
					let res = data;
					console.log(res);
					if(res){
						$('input[name=title]').val(res.title);
						$('.title').html(res.title);

						$('input[name=image]').val(res.thumbnail_url);
						$('.image .thumbnail').attr('src', res.thumbnail_url);
						$('.btn_submit').removeAttr('disabled');
					}
				});
				
				request.fail(function( jqXHR, textStatus ) {
					$( "#overlay" ).hide();  
					// alert( "Request failed: " + textStatus );
					alert( "Request failed: Youtube URL not valid");

					$('.title').html('title');
					$('.image .thumbnail').attr('src', '');
					$('.btn_submit').attr('disabled', '');
				});
          	});
		});
	</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($view_path.'.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>