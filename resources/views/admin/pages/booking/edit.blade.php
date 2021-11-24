@extends($view_path.'.layouts.master')
@section('content')
<form role="form" method="post" action="{{url($path)}}/{{$data->id}}" enctype="multipart/form-data">
	{{ method_field('PUT') }}
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

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'booking_code','label' => 'Booking Code','value' => (old('booking_code') ? old('booking_code') : $data->booking_code),'attribute' => 'required autofocus readonly','form_class' => 'col-md-12', 'required' => 'y'])!!}
				
				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'event_title','label' => 'Event Title','value' => (old('event_title') ? old('event_title') : $data->event_title),'attribute' => 'required autofocus readonly','form_class' => 'col-md-12', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Booked By','value' => (old('name') ? old('name') : $data->name),'attribute' => 'required autofocus readonly','form_class' => 'col-md-12', 'required' => 'y'])!!}
				
				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'phone','label' => 'Phone','value' => (old('phone') ? old('phone') : $data->phone),'attribute' => 'required autofocus readonly','form_class' => 'col-md-12', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'email','label' => 'Email','value' => (old('email') ? old('email') : $data->email),'attribute' => 'required autofocus readonly','form_class' => 'col-md-12', 'required' => 'y'])!!}
				
				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'time','label' => 'Time','value' => (old('time') ? old('time') : $data->time),'attribute' => 'required autofocus readonly','form_class' => 'col-md-12', 'required' => 'y'])!!}

				<div class="col-md-12" id="">
					<!-- <div class="form-group form-md-line-input"> -->
						<label for="form_floating_Hqd">Booking Seat<!--<span class="" aria-required="true">*</span>--></label>
						<small></small>
					<!-- </div> -->
					<table class="table table-bordered table_timeEvent">
						<thead> 
							<tr> 
								<th>No. KAJ</th> 
								<th>Name</th>
								<th>Seat Code</th>
							</tr> 
						</thead>
						<tbody>
							<tr>
							<td>
								<div class="col-md-6" id="">
									<div class="form-group form-md-line-input">
										<input type="text" id="no_kaj" class="form-control no_kaj" name="no_kaj[]" value="" readonly="" placeholder="No. KAJ">
										<!-- <label for="form_floating_Hqd">Event Date <span class="" aria-required="true">*</span></label> -->
										<small></small>
									</div>
								</div>
							</td>
							<td>
								<div class="col-md-6" id="">
									<div class="form-group form-md-line-input">
										<input type="text" id="name" class="form-control name" name="name[]" value="" readonly="" placeholder="Name">
										<!-- <label for="form_floating_Hqd">Event Date <span class="" aria-required="true">*</span></label> -->
										<small></small>
									</div>
								</div>
							</td>
							<td>
								<div class="col-md-6" id="">
									<div class="form-group form-md-line-input">
										<input type="text" id="seat_code" class="form-control seat_code" name="seat_code[]" value="" readonly="" placeholder="Seat Code">
										<!-- <label for="form_floating_Hqd">Event Date <span class="" aria-required="true">*</span></label> -->
										<small></small>
									</div>
								</div>
							</td>
							</tr>
							
							
						</tbody>
					</table>
				</div>

  				<input type="hidden" id="root-url" value="{{$path}}" />
			</div>	
			{!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'n'])!!}
		</div>
	</div>
</form>
@push('custom_scripts')
	<script>
		$(document).ready(function(){
			var password = $(".password");
			
			let clone_row = $('.table_timeEvent').find('tbody tr').first().clone();
			$('.table_timeEvent tbody').html('');

			let $booked_array = <?php echo $data->booked_array;?>;
			console.log($booked_array);
			for (let i = 0; i < $booked_array.length; i++) {
				// if(i == 0){
				// 	$('.table_timeEvent .event_time').val($booked_array[i].time);
				// 	$('.table_timeEvent .event_timeStatus option').removeAttr('selected');
				// 	$(".table_timeEvent .event_timeStatus option[value="+$booked_array[i].status+"]").attr('selected', '');
				// }else{
					let clone_row_2 = clone_row.clone();
					clone_row_2.find('.no_kaj').val($booked_array[i].no_kaj);
					clone_row_2.find('.name').val($booked_array[i].name);
					clone_row_2.find('.seat_code').val($booked_array[i].seat_code);
					
					$('.table_timeEvent tbody').append(clone_row_2);
				// }
			}
		});
	</script>
@endpush
@endsection