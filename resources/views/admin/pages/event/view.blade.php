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
      		<div class="row">
				{!!view($view_path.'.builder.select',['name' => 'church_id','label' => 'Church','value' => (old('church_id') ? old('church_id') : $data->church_id),'attribute' => 'required','form_class' => 'col-md-12', 'data' => $church, 'class' => 'select2 church_id', 'onchange' => ''])!!}

				{!!view($view_path.'.builder.select',['name' => 'room_id','label' => 'Room','value' => (old('room_id') ? old('room_id') : ''),'attribute' => 'required','form_class' => 'col-md-12', 'data' => [], 'class' => 'select2 room_id', 'onchange' => ''])!!}
				
				{!!view($view_path.'.builder.select',['name' => 'category_event','label' => 'Category Event','value' => (old('category_event') ? old('category_event') : $data->category_id),'attribute' => 'required','form_class' => 'col-md-12', 'data' => $category_event, 'class' => 'select2 category_event', 'onchange' => ''])!!}

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'title','label' => 'Title','value' => (old('title') ? old('title') : $data->title),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'title', 'required' => 'y'])!!}
				
				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'sub_title','label' => 'Subtitle','value' => (old('sub_title') ? old('sub_title') : $data->sub_title),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'sub_title', 'required' => 'y'])!!}
				
				{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'content','label' => 'Content','value' => (old('content') ? old('content') : $data->content),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'content', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.file',['name' => 'image','label' => 'Event Image','value' => $data->image,'type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'n'])!!}

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'speaker','label' => 'Speaker','value' => (old('speaker') ? old('speaker') : $data->speaker),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'speaker', 'required' => 'y'])!!}

				<div class="col-md-6" id="">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="event_date" class="form-control" name="event_date" value="{{date_format(date_create($data->date), 'd-m-Y')}}" readonly="" placeholder="Event Date">
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
								<th>Seat Quota</th> 
								<th style="text-align: center;"><!--<span class="glyphicon glyphicon-plus add_eventTime" aria-hidden="true"></span>--></th> 
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
                            <td class="seat_quota"></td>
							<td style="
								text-align: center;
								vertical-align: middle;
							">
								<!--<span class="glyphicon glyphicon-minus remove_eventTime" aria-hidden="true"></span>--></td>
							</tr>
							
							
						</tbody>
					</table>
				</div>

			</div>	
			<!-- {!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green btn_submit','label' => trans('general.submit'),'ask' => 'n'])!!} -->
			</div>
	</div>
</form>
@push('custom_scripts')
	<script>
		$(document).ready(function(){
            // $('input').attr('readonly','');
            $('input, textarea, select').attr('disabled','');
            $("input[type='file']").hide();
            $(".remove-single-image").attr('disabled','');
            // $('.form-md-line-input').hide();
			let $room = <?php echo $room;?>;
			let $room_id = <?php echo $data->room_id;?>;

			console.log($('.church_id').val());
			if($('.church_id').val() != ''){
				let church_id = parseInt($('.church_id').val());
				appendRoom(church_id, $room_id);
			}

			$('.church_id').change(function(){
				let church_id = parseInt($(this).val());
				console.log($room);
				if(church_id != ""){
					appendRoom(church_id);
					// let clone_option = $('.room_id').find('option').first().clone();
					// clone_option.removeAttr('selected');
					// $('.room_id').find('option.room_option').remove();
					// $(".room_id").val('').change();

					// for (let i = 0; i < $room.length; i++) {
					// 	if($room[i].church_id == church_id){
					// 		let clone_option_2 = clone_option.clone();
					// 		clone_option_2.addClass('room_option');
					// 		clone_option_2.addClass('room_church-'+$room[i].church_id);
					// 		clone_option_2.val($room[i].id);
					// 		clone_option_2.html($room[i].room_name);
					// 		console.log(clone_option_2);
					// 		$('.room_id').append(clone_option_2);
					// 	}
					// }
				}
			});

			function appendRoom(church_id, room_id = ''){
				console.log('appendRoom');
				let clone_option = $('.room_id').find('option').first().clone();
				clone_option.removeAttr('selected');
				$('.room_id').find('option.room_option').remove();
				$(".room_id").val('').change();

				// console.log($room);
				for (let i = 0; i < $room.length; i++) {
					// console.log($room[i].church_id, church_id);
					if($room[i].church_id == church_id){
						let clone_option_2 = clone_option.clone();
						clone_option_2.addClass('room_option');
						clone_option_2.addClass('room_church-'+$room[i].church_id);
						clone_option_2.val($room[i].id);
						clone_option_2.html($room[i].room_name);
					
						// console.log(clone_option_2);
						$('.room_id').append(clone_option_2);
						if(room_id != ''){
							// console.log($room[i].id, room_id);
							if($room[i].id == room_id){
								$('.room_id').find('option').removeAttr('selected');
								clone_option_2.attr('selected', '');
							}
						}
					}
				}

				$('.room_id ').change();
			}

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
				console.log('test');
				let clone_row_time_2 = clone_row_time.clone();
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

			let $event_time = <?php echo $time_event;?>;
			console.log($event_time);
			for (let i = 0; i < $event_time.length; i++) {
				if(i == 0){
					$('.table_timeEvent .event_time').val($event_time[i].time);
					$('.table_timeEvent .event_timeStatus option').removeAttr('selected');
					$(".table_timeEvent .event_timeStatus option[value="+$event_time[i].status+"]").attr('selected', '');
                    $('.table_timeEvent .seat_quota').html($event_time[i].total_booked_seat+' / '+$event_time[i].total_available_seat);
				}else{
					let clone_row_time_2 = clone_row_time.clone();
					clone_row_time_2.find('.event_time').val($event_time[i].time);
					clone_row_time_2.find('.event_timeStatus option').removeAttr('selected');
					clone_row_time_2.find(".event_timeStatus option[value="+$event_time[i].status+"]").attr('selected', '');
                    clone_row_time_2.find('.seat_quota').html($event_time[i].total_booked_seat+' / '+$event_time[i].total_available_seat);

                    $('.table_timeEvent tbody').append(clone_row_time_2);

					$('.event_time').timepicker({
						showSeconds: false,
						showMeridian: false
					});

					$('.remove_eventTime').click(function(){
						console.log($(this).parents('tr'));
						$(this).parents('tr').remove();
					});
				}
			}
		});
	</script>
@endpush
@endsection