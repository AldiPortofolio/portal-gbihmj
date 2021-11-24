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
<form role="form" method="post" action="{{url($path)}}/{{$data->id}}" enctype="multipart/form-data">
	{{  method_field('PUT') }}
		
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
							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Church Name','value' => (old('name') ? old('name') : $data->name),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'church_name', 'required' => 'y'])!!}
							
							{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'address','label' => 'Address','value' => (old('address') ? old('address') : $data->address),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'church_name', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'province','label' => 'Province','value' => (old('province') ? old('province') : $data->province),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'province', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'city','label' => 'City','value' => (old('city') ? old('city') : $data->city),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'city', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'region_district','label' => 'Region District','value' => (old('region_district') ? old('region_district') : $data->region_district),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'region_district', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'latitude','label' => 'Latitude','value' => (old('latitude') ? old('latitude') : $data->latitude),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'latitude', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'longitude','label' => 'longitude','value' => (old('longitude') ? old('longitude') : $data->longitude),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'longitude', 'required' => 'y'])!!}
						</div>
					</div>

					<div class="tab-pane" id="images">	
						<div class="row">
							<div class="form-group form-md-line-input col-md-12">
								<label>Church Image <span class="required" aria-required="true"></span></label><br>
								<label class="btn green input-file-label-image">
									<input type="file" class="form-control col-md-12 image_wrapper multiple-image-custom" name="images_upload" data-upload="../upload_image"> Pilih File
										</label>
									<br>
								<small>Note: File Must jpeg,png,jpg,gif | Max. File Size: 200KB</small>
							</div>

							<table class="table table-borded table-responsive table-striped " id="table-list">
								<thead class="table-dark">
									<tr>
										<th>Image</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="seat_tbody">
									<tr class="seat_row" id="">
										<td style="width: 25%;"><img class="img-responsive image_church" src="{{$base_url.'/'.$image_path.'zM7lYx_responsive_design_311890.jpg'}}"></td>
										<td style=""><input type="checkbox" id="checkbox_form_1" class="md-check image_status" name="image_status[]" value="y" checked style="margin: 0;"></td>
									</tr>
								</tbody>
							</table>
							<!-- <div class="btn blue" id="add"><span class="glyphicon glyphicon-plus-sign"></span><span style="margin-left: 5px;">Add New Seat</span></div> -->
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
				onDelete: function(columnsEd) {
					console.log('onDelete');
					// setButtonSubmitEnable();
					console.log(this);
				}, 
				onBeforeDelete: function() {
					console.log('onBeforeDelete');
					console.log($(this));
					console.log($(this).attr('index'));
				},
			});
			let clone_row = $('#seat_tbody tr').first().clone();
			let base_url = <?php echo "'".$base_url."'";?>;
			let path_image = <?php echo "'".$base_url.'/'.$image_path."'";?>;
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

			let church_images = <?php echo $church_images;?>;

			if(church_images.length > 0){
				//append church_images
				for (let i = 0; i < church_images.length; i++) {
					let curr_ci = church_images[i];
					
					let clone_row_2 = clone_row.clone();
					clone_row_2.attr('id', 'seat_row-'+curr_ci.id)
					clone_row_2.find('.image_church').attr('src', path_image+curr_ci.image);
					clone_row_2.find('#bElim').attr('index', 'bElim-'+curr_ci.id);
					clone_row_2.find('#bElim').removeAttr('onclick');
					
					if(church_images[i].status == 'n'){
						clone_row_2.find('.image_status').removeAttr('checked');
						clone_row_2.find('.image_status').val('n');
					}
					console.log(clone_row_2);
					$('#seat_tbody').append(clone_row_2);
				}
			}


			checkboxSeatStatus();
			function checkboxSeatStatus(){
				console.log('checkboxStatus');
				$('.image_status').click(function(){
					let id = $(this).parents('tr').attr('id');
					id = id.split('-')[1];
					// console.log(id);
					// console.log($(this));
					// console.log($(this).val());
					if($(this).val() == 'n'){
						edit_status_church_image(id, 'y');
						// $(this).val('y');
						// $(this).parent('td').find('.image_status_2').val('y');
					}else{
						edit_status_church_image(id, 'n');
						// $(this).val('n');
						// $(this).parent('td').find('.image_status_2').val('n');
					}
				});

				$('.seat_tbody button').click(function(){
					console.log('test');
					setButtonSubmitEnable();
				})
			}

			event_delete_image();
			function event_delete_image(){
				$('button#bElim').click(function(e){
					let id = $(this).attr('index');
					id = id.split('-')[1];
					console.log(id);

					delete_church_image(id);
				});
			}

			//set btn_submit disable
			// $('.btn_submit').attr('disabled', '');

			function setButtonSubmitEnable(){
				let element_seat_row = $('#seat_tbody .seat_row');
				console.log(element_seat_row.length);
				if(element_seat_row.length > 0){
					$('.btn_submit').removeAttr('disabled');
				}else{
					$('.btn_submit').attr('disabled', '');
				}
			}

			function edit_status_church_image(id, status){
				// let url = base_url+'/_admin/church/manage-church/edit_status_church_image?id='+id+'&status='+status;
				let url = base_url+'/api/church/edit_status_church_image?id='+id+'&status='+status;
				$.getdata(url).success(function(res){
					res = JSON.parse(res);
					console.log(res);
					if(res.status_code == 1){
						let data = res.data;
						// console.log('#seat_row-'+id+' .image_status');
						// console.log($('#seat_row-'+id+' .image_status'));
						$('#seat_row-'+id+' .image_status').val(data.status);
					}
				});
			}

			function delete_church_image(id){
				let url = base_url+'/api/church/delete_church_image';
				let formdata    = {id:id};
				$.postdata(url, formdata).success(function(res){
					res = JSON.parse(res);
					console.log(res);
					if(res.status_code == 1){
						$('#seat_row-'+id).remove();
					}
				});
			}

			$(document).on('change','.multiple-image-custom',function(res){
				if(res){
					name        = $(this).attr('name');
					url    = base_url+'/api/church/upload_image';
					church_id   = <?php echo $data->id;?>;
					files       = this.files;
					ext         = files[0].name.split('.')[1].toLowerCase();
					size        = files[0].size;
					allow_ext   = ['jpg','gif','png','jpeg'];
					if($.inArray(ext,allow_ext) > -1){
						if(size <= 200000){ //max file size 1KB
							$('input[name='+name).attr('name','images_upload');
							formdata = new FormData();
							formdata.append('church_id', church_id);
							console.log(files[0]);
							console.log(files[0].value);
							formdata.append('image', files[0]);
							console.log(formdata);
							$.postformdata(url,formdata).success(function(data){
							// let formdata    = {church_id: church_id, image: files[0].value};
							// $.postdata(url, formdata).success(function(res){	
								console.log(data);
								data = JSON.parse(data);
								if(data.status_code == 1){
									//add row
									let data2 = data.data;
									let clone_row_2 = clone_row.clone();
									console.log(path_image+data);
									clone_row_2.attr('id', 'seat_row-'+data2.id)
									clone_row_2.find('.image_church').attr('src', path_image+data2.image);
									clone_row_2.find('#bElim').attr('index', 'bElim-'+data2.id);
									clone_row_2.find('#bElim').removeAttr('onclick');
									console.log(clone_row_2);
									$('#seat_tbody').append(clone_row_2);

									checkboxSeatStatus();
									event_delete_image();
								}
							});
						}else{
							alert("File size is to large");
						}
					}else{
						alert ("File must image");
					}
				}
			});
		});
	</script>
@endpush
@endsection