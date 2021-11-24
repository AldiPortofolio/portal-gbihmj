
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
				<?php echo view($view_path.'.builder.select',['name' => 'church_id','label' => 'Church','value' => (old('church_id') ? old('church_id') : ''),'attribute' => 'required','form_class' => 'col-md-12', 'data' => $church, 'class' => 'select2 church_id', 'onchange' => '']); ?>


				<?php echo view($view_path.'.builder.select',['name' => 'room_id','label' => 'Room','value' => (old('room_id') ? old('room_id') : ''),'attribute' => 'required','form_class' => 'col-md-12', 'data' => [], 'class' => 'select2 room_id', 'onchange' => '']); ?>

				
				<?php echo view($view_path.'.builder.select',['name' => 'category_event','label' => 'Category Event','value' => (old('category_event') ? old('category_event') : ''),'attribute' => 'required','form_class' => 'col-md-12', 'data' => $category_event, 'class' => 'select2 category_event', 'onchange' => '']); ?>


				<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'title','label' => 'Title','value' => (old('title') ? old('title') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'title', 'required' => 'y']); ?>

				
				<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'sub_title','label' => 'Subtitle','value' => (old('sub_title') ? old('sub_title') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'sub_title', 'required' => 'y']); ?>

				
				<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'content','label' => 'Content','value' => (old('content') ? old('content') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'content', 'required' => 'y']); ?>


				<?php echo view($view_path.'.builder.file',['name' => 'image','label' => 'Event Image','value' => '','type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'n']); ?>


				<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'speaker','label' => 'Speaker','value' => (old('speaker') ? old('speaker') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'speaker', 'required' => 'y']); ?>


				<div class="col-md-6" id="">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="event_date" class="form-control" name="event_date" value="" readonly="" placeholder="Event Date">
		            <label for="form_floating_Hqd">Event Date <!--<span class="" aria-required="true">*</span>--></label>
		            <small></small>
		          </div>
		        </div>

				<div class="col-md-12" id="">
					<!-- <div class="form-group form-md-line-input"> -->
						<label for="form_floating_Hqd">Event Time <!--<span class="" aria-required="true">*</span>--></label>
						<small></small>
					<!-- </div> -->
					<table class="table table-bordered table_timeEvent">
						<thead> 
							<tr> 
								<th>Time</th> 
								<th>Status</th> 
								<th style="text-align: center;"><span class="glyphicon glyphicon-plus add_eventTime" aria-hidden="true"></span></th> 
							</tr> 
						</thead>
						<tbody>
							<tr>
							<td>
								<div class="col-md-6" id="">
									<div class="form-group form-md-line-input">
										<input type="text" id="event_time" class="form-control event_time" name="event_time[]" value="" readonly="" placeholder="Time">
										<!-- <label for="form_floating_Hqd">Event Date <span class="" aria-required="true">*</span></label> -->
										<small></small>
									</div>
								</div>
							</td>
							<td>
								<div class="col-md-6 form-group form-md-line-input" style="">
									<select class="form-control event_timeStatus" name="event_timeStatus[]" required="" onchange="" tabindex="-1" aria-hidden="true">
										<option value="1">Active</option>
										<option value="0">Non Active</option>
										<!-- <option value="0">TANPA PENEGUHAN BAPTIS HMJ</option>
										<option value="1">AKAN MENGIKUTI BAPTISAN BERIKUT</option>
										<option value="2">Baptism Date</option> -->
									</select>
									<!-- <label for="form_floating_Hqd">Time Status <span class="required" aria-required="true">*</span></label> -->
									<small></small>
								</div>
							</td>
							<td style="
								text-align: center;
								vertical-align: middle;
							">
								<span class="glyphicon glyphicon-minus remove_eventTime" aria-hidden="true"></span></td>
							</tr>
							
							
						</tbody>
					</table>
				</div>

			</div>	
			<?php echo view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green btn_submit','label' => trans('general.submit'),'ask' => 'n']); ?>

		</div>
	</div>
</form>
<?php $__env->startPush('custom_scripts'); ?>
	<script>
		$(document).ready(function(){
			let $room = <?php echo json_encode($room);?>;

			$('.church_id').change(function(){
				let church_id = $(this).val();
				console.log($room);
				if(church_id != ""){
					let clone_option = $('.room_id').find('option').first().clone();
					clone_option.removeAttr('selected');
					$('.room_id').find('option.room_option').remove();
					$(".room_id").val('').change();

					for (let i = 0; i < $room.length; i++) {
						if($room[i].church_id == church_id){
							let clone_option_2 = clone_option.clone();
							clone_option_2.addClass('room_option');
							clone_option_2.addClass('room_church-'+$room[i].church_id);
							clone_option_2.val($room[i].id);
							clone_option_2.html($room[i].room_name);
							console.log(clone_option_2);
							$('.room_id').append(clone_option_2);
						}
					}
				}
			});

			$("#event_date").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,

              	onSelect: function(dateText, inst) {

              	}
          	});

			$('.event_time').timepicker({
				showSeconds: false,
                showMeridian: false
			});

			let clone_row_time = $('.table_timeEvent').find('tbody tr').first().clone();
			$('.table_timeEvent .remove_eventTime').hide()
			$('.add_eventTime').click(function(){
				// console.log('test');
				clone_row_time_2 = clone_row_time.clone();
				$('.table_timeEvent tbody').append(clone_row_time_2);

				$('.event_time').timepicker({
					showSeconds: false,
					showMeridian: false
				});

				$('.remove_eventTime').click(function(){
					console.log($(this).parents('tr'));
					$(this).parents('tr').remove();
				});
			});
		});
	</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($view_path.'.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>