@extends($view_path.'.layouts.master')
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
	  			<div class="row">
					<div class="col-md-6">
		                <div class="form-group" style=''>
		                      <label for="tag">Category Modem</label>
		                      <select class="select2" name="category_modem">
		                        @foreach($category_modem as $u)
		                            <option value="{{$u->id}}" {{old('category_modem') ? ($u->id == old('category_modem') ? 'selected' : '') : ''}}>{{$u->category_modem_name}}</option>
		                        @endforeach
		                      </select>
		                </div>
		            </div>

  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Name','value' => (old('name') ? old('name') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'serial_no','label' => 'Serial No.','value' => (old('serial_no') ? old('serial_no') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'imei','label' => 'IMEI','value' => (old('imei') ? old('imei') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'description','label' => 'Description','value' => (old('description') ? old('description') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6'])!!}

				<div class="col-md-6">
	                <div class="form-group" style=''>
	                      <label for="tag">Modem Status</label>
	                      <select class="select2" name="modem_status">
	                        @foreach($modem_status as $u)
	                            <option value="{{$u->id}}" {{old('modem_status') ? ($u->id == old('modem_status') ? 'selected' : '') : ''}}>{{$u->modem_status_name}}</option>
	                        @endforeach
	                      </select>
	                </div>
	            </div>

	            <div class="col-md-6">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="birth_date" class="form-control" name="date_of_entry" value="" readonly="" placeholder="Date of Entry">
		            <label for="form_floating_Hqd"> Date of Entry<span class="" aria-required="true">*</span></label>
		            <small></small>
		          </div>
		        </div>

		        <div class="col-md-6">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="activation_date" class="form-control datetimepicker" name="activation_date" value="" readonly="" placeholder="Activation Date">
		            <label for="form_floating_Hqd"> Activation Date<span class="" aria-required="true">*</span></label>
		            <small></small>
		          </div>
		        </div>

		        <div class="col-md-6">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="deactivation_date" class="form-control datetimepicker" name="deactivation_date" value="" readonly="" placeholder="Deactivation Date">
		            <label for="form_floating_Hqd"> Deactivation Date<span class="" aria-required="true">*</span></label>
		            <small></small>
		          </div>
		        </div>

		        {!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'password','label' => 'Password','value' => (old('password') ? old('password') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6'])!!}

		        {!!view($view_path.'.builder.text',['type' => 'text','name' => 'weight','label' => 'Weight (Gram)','value' => (old('weight') ? old('weight') : 600),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

			  	{!!view($view_path.'.builder.file',['name' => 'image','label' => 'Image','value' => '','type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif | Best Resolution: 138 x 44 px','form_class' => 'col-md-12', 'required' => 'n'])!!}

			</div>	
			{!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'y'])!!}
		</div>
	</div>
</form>
@push('custom_scripts')
	<script>
		$(document).ready(function(){
			var password = $(".password");
			var username = $(".username");
			var name = $(".name");
			
			$('#generate-password').on('click', function(e){
	        	var randomstring = Math.random().toString(36).slice(-6);
		        password.val(randomstring);
		    });

		    $('#show-password').on('click', function(e){
		        if(password.attr("type") == "password"){
		            password.attr("type", "text");
		            $("#show-password").addClass("text-primary");
		            $("#show-password").removeClass("text-default");
		        }
		        else{
		            password.attr("type", "password");
		            $("#show-password").addClass("text-default");
		            $("#show-password").removeClass("text-primary");
		        }
		    });

		    $("#birth_date").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,

              	onSelect: function(dateText, inst) {

              	}

          	});

          	$('.user_access').on('change', function(e){
	        	if(this.value == 2){
	        		$("#outlet").prop('disabled', true);
	        	}else{
	        		$("#outlet").prop('disabled', false);
	        	}
		    });

		    $('.name').keyup(function(){ 
		    	console.log('keyup');
		      	var slug = convertToUsername($(this).val());
		      	$(".username").val(slug);    
		    });

		    function convertToUsername(Text)
		    {
		        return Text
		            .toLowerCase()
		            .replace(/ /g,'')
		            .replace(/[^\w-]+/g,'')
		            ;
		    }
		});
	</script>
@endpush
@endsection