
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
	#bEdit{
		display: none;
	}
	.btn#add{
		margin-bottom: 20px;
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
	  		<div class="tabbable-line">
		        <ul class="nav nav-tabs">
		          <li class="active">
		            <a href="#room" data-toggle="tab" aria-expanded="true">Room</a>
		          </li>
		          <li>
		            <a href="#seat" data-toggle="tab" aria-expanded="false">Seat</a>
		          </li>
		        </ul>
		        <div class="tab-content">
		          	<div class="tab-pane active" id="room">	
						<div class="row">
							<?php echo view($view_path.'.builder.select',['name' => 'church_id','label' => 'Church','value' => (old('church_id') ? old('church_id') : ''),'attribute' => 'required','form_class' => 'col-md-12', 'data' => $church, 'class' => 'select2 user_access', 'onchange' => '']); ?>


							<?php echo view($view_path.'.builder.text',['type' => 'text','name' => 'room_name','label' => 'Room Name','value' => (old('room_name') ? old('room_name') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'room_name', 'required' => 'y']); ?>


							<?php echo view($view_path.'.builder.file',['name' => 'denah_image','label' => 'Denah Image','value' => '','type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'n']); ?>

						</div>
					</div>

					<div class="tab-pane" id="seat">	
						<div class="row">
							<?php echo view($view_path.'.builder.text',['type' => 'number','name' => 'total_seat','label' => 'Total Seat','value' => (old('total_seat') ? old('total_seat') : 1),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'total_seat', 'required' => 'y']); ?>


							<div class="form-group col-md-6" style="">
								<div class="btn blue create_table" style="margin-bottom: 20px;">Create Table Seat</div>							

							</div>

							<div class="col-md-12 seat_array_wrapper" style="">
								<div class="table-content">
								<div style="
									font-size: 16px;
									font-weight: bold;
								">Table Seat</div>
									<table class="table table-borded table-responsive table-striped " id="table-list">
										<thead class="table-dark">
											<tr>
												<th>Seat Code</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody id="seat_tbody">
											<tr id="seat_row">
												<td style=""><input type="text" id="" class="md-check seat_code" name="seat_code[]" value="-" style="margin: 0;"></td>
												<td style=""><input type="hidden" class="seat_status_2" name="seat_status_2[]" value="1"><input type="checkbox" id="checkbox_form_1" class="md-check seat_status" name="seat_status[]" value="1" checked style="margin: 0;"></td>
											</tr>
										</tbody>
									</table>
									<div class="btn blue" id="add"><span class="glyphicon glyphicon-plus-sign"></span><span style="margin-left: 5px;">Add New Seat</span></div>
								</div>    				
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green btn_submit','label' => trans('general.submit'),'ask' => 'n']); ?>

		</div>
	</div>
</form>
<?php $__env->startPush('scripts'); ?>
	<script src="<?php echo e(asset('components/plugins/Editable-Tables-jQuery-Bootstrap-Bootstable/bootstable.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('custom_scripts'); ?>
	<script>
		$(document).ready(function(){
			//set table 
			$("#table-list").SetEditable({
				columnsEd: "0",
				onDelete: function() {
					console.log('onDelete');
					setButtonSubmitEnable();
				}, 
			});
			let clone_row = $('#seat_tbody tr').first().clone();
			$('#seat_tbody').html('');

			$('.create_table').click(function(){
				$('#seat_tbody').html('');
				let total_seat = $('.total_seat').val();

				for (let i = 0; i < total_seat; i++) {
					let clone_row_2 = clone_row.clone();
					
					$('#seat_tbody').append(clone_row_2);
				}

				checkboxSeatStatus();
			});

			$('#add').click(function() {
				rowAddNew('table-list', ['<input type="text" id="" class="md-check seat_code" name="seat_code[]" value="-" style="margin: 0;">','<input type="hidden" class="seat_status_2" name="seat_status_2[]" value="1"><input type="checkbox" id="checkbox_form_1" class="md-check seat_status" name="seat_status[]" value="1" checked style="margin: 0;">',3]);
				$('#seat_tbody tr:last-child').find('td:first-child').addClass('seat_code');
				
				checkboxSeatStatus();
			});

			checkboxSeatStatus();
			function checkboxSeatStatus(){
				console.log('checkboxSeatStatus');
				$('.seat_status').click(function(){
					console.log($(this));
					console.log($(this).parent('td').find('.seat_status_2'));
					if($(this).val() == 0){
						$(this).val(1);
						$(this).parent('td').find('.seat_status_2').val(1);
					}else{
						$(this).val(0);
						$(this).parent('td').find('.seat_status_2').val(0);
					}
				});

				$('.seat_tbody button').click(function(){
					console.log('test');
					setButtonSubmitEnable();
				})
			}

			//set btn_submit disable
			$('.btn_submit').attr('disabled', '');

			function setButtonSubmitEnable(){
				let element_seat_row = $('#seat_tbody #seat_row');
				console.log(element_seat_row.length);
				if(element_seat_row.length > 0){
					$('.btn_submit').removeAttr('disabled');
				}else{
					$('.btn_submit').attr('disabled', '');
				}
			}


			// //event mode_seat_array
			// if($(".mode_seat_array:checked").length > 0){
			// 	$(".mode_seat_array").val('y');
			// 	$('.seat_array_wrapper').show();

			// 	$('.btn_submit').attr('disabled', '');
			// 	$('.total_seat').attr('readonly', '');
			// }
			// $('.mode_seat_array').on('click', function(e){
	        // 	if($(".mode_seat_array:checked").length > 0){
			// 		$(this).val('y');
			// 		$('.seat_array_wrapper .seat_array').attr("required",""); 
	        // 		$(".seat_array_wrapper").show();
					
			// 		$('.btn_submit').attr('disabled', '');
			// 		$('.total_seat').attr('readonly', '');
	        // 	}else{
			// 		$(this).val('n');
			// 		$('.seat_array_wrapper .seat_array').removeAttr("required",""); 
	        // 		$(".seat_array_wrapper").hide();

			// 		$('.total_seat').removeAttr('readonly', '');
	        // 	}
		    // });

			// checkArray();
			// $('.btn_check_array').click(function(){
			// 	checkArray();
			// })

			// $('.seat_array').keyup(function(){
			// 	$('.btn_submit').attr('disabled', '');
			// 	$('.check_result').hide();
			// })

			// function checkArray(){
			// 	let arr = $('.seat_array').val();
			// 	let is_array = false;
			// 	let valid_array = false;
			// 	try {
			// 		// console.log(JSON.parse(arr));
			// 		arr = JSON.parse(arr);
			// 		is_array = Array.isArray(arr);
			// 		//set total_seat
			// 		let total_seat = 0;
			// 		for (let i = 0; i < arr.length; i++) {
			// 			for (let j = 0; j < arr[i].length; j++) {
			// 				console.log(arr[i])
			// 				console.log(arr[i][j])
			// 				console.log(arr[i][j].seat_code, arr[i][j].status)
			// 				if(arr[i][j].seat_code != undefined && arr[i][j].status != undefined){
			// 					if(arr[i][j].status == 1){
			// 						total_seat += 1;
			// 					}
			// 				}
			// 			}
			// 		}

			// 		if(total_seat > 0){
			// 			$('.total_seat').val(total_seat);
			// 			valid_array = true;
			// 		}
			// 	} catch (error) {
			// 		console.log(error);
			// 	}
			// 	console.log(is_array, valid_array);
			// 	if(is_array && valid_array){
			// 		$('.check_result').html('Array valid');
			// 		$('.check_result').css('color', '##3598dc');
			// 		$('.check_result').show();

			// 		$('.btn_submit').removeAttr('disabled');
			// 	}else{
			// 		$('.check_result').html('Array not valid !');
			// 		$('.check_result').css('color', '#e02222');
			// 		$('.check_result').show();
			// 	}
			// }
		});
	</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($view_path.'.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>