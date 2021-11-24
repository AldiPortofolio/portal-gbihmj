@extends($view_path.'.layouts.master')
@push('css')
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
@endpush
@section('content')
<form role="form" method="post" action="{{url($path)}}" enctype="multipart/form-data">
	<div class="portlet light bordered">
    	<div class="portlet-title">
			<div class="caption font-green">
				<i class="icon-layers font-green title-icon"></i>
				<span class="caption-subject bold uppercase"> {{$title}}</span>
			</div>
			<div class="actions">
				<a href="{{url($path)}}"><button type="button" class="btn red-mint">{{trans('general.back')}}</button></a>
			</div>
    	</div>
    	<div class="portlet-body form">
      		@include('admin.includes.errors')
	  		<div class="tabbable-line">
		        <ul class="nav nav-tabs">
		          <li class="active">
		            <a href="#church" data-toggle="tab" aria-expanded="true">Church</a>
		          </li>
		          <li>
		            <a href="#images" data-toggle="tab" aria-expanded="false">Images</a>
		          </li>
		        </ul>
		        <div class="tab-content">
		          	<div class="tab-pane active" id="church">	
						<div class="row">
							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Church Name','value' => (old('name') ? old('name') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'church_name', 'required' => 'y'])!!}
							
							{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'address','label' => 'Address','value' => (old('address') ? old('address') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'church_name', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'province','label' => 'Province','value' => (old('province') ? old('province') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'province', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'city','label' => 'City','value' => (old('city') ? old('city') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'city', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'region_district','label' => 'Region District','value' => (old('region_district') ? old('region_district') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'region_district', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'latitude','label' => 'Latitude','value' => (old('latitude') ? old('latitude') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'latitude', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'longitude','label' => 'longitude','value' => (old('longitude') ? old('longitude') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'longitude', 'required' => 'y'])!!}
						</div>
					</div>

					<div class="tab-pane" id="images">	
						<div class="row">
							<!-- {!!view($view_path.'.builder.file',['name' => 'image','label' => 'Church Image','value' => '','type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'multiple-image','class' => 'col-md-12 image_wrapper','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'n', 'attribute' => 'data-upload=ext/upload_image'])!!} -->
							<div class="col-md-12" style="margin-bottom: 20px;">
								Please submit data church before
							</div>
						</div>
					</div>
				</div>
			</div>
			{!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green btn_submit','label' => trans('general.submit'),'ask' => 'n'])!!}
		</div>
	</div>
</form>
@push('scripts')
	<script src="{{asset('components/plugins/Editable-Tables-jQuery-Bootstrap-Bootstable/bootstable.min.js')}}"></script>
@endpush
@push('custom_scripts')
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
@endpush
@endsection