@extends($view_path.'.layouts.master')
@section('content')
<form role="form" method="post" action="{{url($path)}}/{{$user->id}}" enctype="multipart/form-data">
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
  				{!!view($view_path.'.builder.select',['name' => 'user_access_id','label' => 'User Access','value' => (old('user_access_id') ? old('user_access_id') : $user->user_access_id),'attribute' => 'required','form_class' => 'col-md-12 display_none', 'data' => $user_access, 'class' => 'select2 user_access_id', 'onchange' => ''])!!}

				{!!view($view_path.'.builder.text',['type' => 'number','name' => 'phone','label' => 'Phone','value' => (old('phone') ? old('phone') : $user->phone),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'required' => 'y'])!!}

  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Name','value' => (old('name') ? old('name') : $user->name),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.text',['type' => 'email','name' => 'email','label' => 'Email','value' => (old('email') ? old('email') : $user->email),'attribute' => 'required autofocus','form_class' => 'col-md-12'])!!}

				<div class="col-md-6" id="birth_date-col">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="birth_date" class="form-control" name="birth_date" value="{{$user->birth_date != '0000-00-00' ? date_format(date_create($user->birth_date),'d-m-Y') : ''}}" readonly="" placeholder="01-01-2021">
		            <label for="form_floating_Hqd">Birth Date <span class="" aria-required="true">*</span></label>
		            <small></small>
		          </div>
		        </div>

				<div class="col-md-12">
					<div class="form-group form-md-radios">
						<label for="form_control">Gender</label>
						<div class="md-radio-inline" 0="">
							<div class="md-radio">
								<input type="radio" id="checkbox_male" class="md-radiobtn" name="gender" 0="" checked="" value="male" onclick="" {{isset($user->gender) ? ($user->gender == 'm' ? 'checked' : '') : ''}}>
								<label for="checkbox_male">
									<span></span>
									<span class="check"></span>
									<span class="box"></span>
									Male
								</label>
							</div>
							<div class="md-radio">
								<input type="radio" id="checkbox_female" class="md-radiobtn" name="gender" 1="" value="female" onclick="" {{isset($user->gender) ? ($user->gender == 'f' ? 'checked' : '') : ''}}>
								<label for="checkbox_female">
									<span></span>
									<span class="check"></span>
									<span class="box"></span>
									Female
								</label>
							</div>
							<small></small>
						</div>
					</div>
				</div>

				{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'address','label' => 'Address','value' => (old('address') ? old('address') : $user->address),'attribute' => 'required autofocus','form_class' => 'col-md-12'])!!}

			  	<div class="form-group col-md-2" style="display: none;">
				  	<div class="md-checkbox">
				  		<label>Login Backend</label>
						<input type="checkbox" id="checkbox_form_1" class="md-check login_web" name="login_web" {{isset($user->login_backend) ? ($user->login_backend == 'y' ? 'checked' : '') : ''}} value="y" >
						<label for="checkbox_form_1">
							<span></span>
					        <span class="check"></span>
					        <span class="box"></span>
						</label>
					</div>
				</div>

				<div class="col-md-12 password_wrapper" style="display: none;">
					<div class="form-group form-md-line-input" hre="">
						<label for="form_floating_hre">Password <span class="required" aria-required="true">*</span></label>
				 		<div class="input-group">
				      		<input type="password" class="form-control password" id="password" name="password" placeholder="Password" value="">
				      		<span class="input-group-addon">
	                            <a id="show-password" class="text-default"><i class="fa fa-eye"></i></a>
	                        </span>

	                        <span class="input-group-btn">
	                            <a id="generate-password" class="btn btn-primary">Generate</a>
	                        </span>
				    	</div>
					
						<small></small>
					</div>
				</div>
				
			  	{!!view($view_path.'.builder.file',['name' => 'picture','label' => 'Picture','value' => $user->images,'type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'n'])!!}

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'no_kaj','label' => 'No. KAJ','value' => (old('no_kaj') ? old('no_kaj') : $user->no_kaj),'attribute' => 'autofocus','form_class' => 'col-md-6'])!!}

				<div class="col-md-6" id="">
					<div class="form-group form-md-line-input">
						<input type="text" id="join_date" class="form-control" name="join_date" value="{{$user->join_date != '0000-00-00' ? date_format(date_create($user->join_date),'d-m-Y') : ''}}" readonly="" placeholder="01-01-2021">
						<label for="form_floating_Hqd">Join Date <!--<span class="" aria-required="true">*</span>--></label>
						<small></small>
					</div>
				</div>

				<div class="col-md-12" id="">
					<div class="form-group form-md-line-input" style="display: inline-block;">
						<select class="form-control select2 baptism_status select2-hidden-accessible" name="baptism_status" required="" onchange="" tabindex="-1" aria-hidden="true">
							<option value="">--Select Babptism Status--</option>
							@if (count($baptism_status_arr) > 0)
								@foreach($baptism_status_arr as $d => $q)
									<option value={{$d}} {{$user->baptism_status == $d ? 'selected' : ''}}>{{$q}}</option>
								@endforeach
							@endif
						</select>
						<label for="form_floating_Hqd">Baptism Status <span class="required" aria-required="true">*</span></label>
		            	<small></small>
					</div>

					<div class="" style="display: inline-block;">
						<input type="text" id="baptism_date" class="form-control readonly" name="baptism_date" value="{{($user->baptism_date != null && $user->baptism_date != '0000-00-00') ? date_format(date_create($user->baptism_date),'d-m-Y') : ''}}" placeholder="01-01-2021" style="display: none;">
					</div>
				</div>

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'no_baptism','label' => ' No. Baptism','value' => (old('no_baptism') ? old('no_baptism') : $user->baptism_no),'attribute' => 'autofocus','form_class' => 'col-md-6'])!!}

				<div class="col-md-12">
					<div class="form-group form-md-radios">
						<label for="form_control">Status KAJ</label>
						<div class="md-radio-inline" 0="">
							<div class="md-radio">
								<input type="radio" id="checkbox_expired" class="rd_status_kaj" name="status_kaj" 0="" value="e" onclick="" {{$user->kaj_status == 'e' ? 'checked' : ''}}>
								<label for="checkbox_expired">
									<span></span>
									<span class="check"></span>
									<span class="box"></span>
									Expired
								</label>
							</div>
							
							
							<div class="md-radio">
								<input type="radio" id="checkbox_expired_until" class="rd_status_kaj" name="status_kaj" 1="" value="eu" onclick="" {{$user->kaj_status == 'eu' ? 'checked' : ''}}>
								<label for="checkbox_expired_until">
									<span></span>
									<span class="check"></span>
									<span class="box"></span>
									Expired Until
								</label>
							</div>

							<div class="md-radio">
								<input type="text" id="expired_date" class="form-control readonly" name="expired_date" value="{{($user->kaj_expired_date != null && $user->kaj_expired_date != '0000-00-00') ? date_format(date_create($user->kaj_expired_date),'d-m-Y') : ''}}" placeholder="01-01-2021" style="display: none;">
							</div>
							<small></small>
						</div>
					</div>
				</div>

				{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'information','label' => 'Information','value' => (old('information') ? old('information') : $user->information),'attribute' => 'autofocus','form_class' => 'col-md-12'])!!}

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

          	var user_access_id = $(".user_access_id");
          	if(user_access_id.val() == '2'){
          		$("#outlet").prop( "disabled", true );
          	}else{
          		$("#operator").removeAttr("disabled");
          	}

          	user_access_id.change(function(){
          		$("#outlet").prop( "disabled", true );
          		console.log(user_access_id.val());
          		if(user_access_id.val() != '2'){
	          		$("#outlet").removeAttr("disabled");
	          	}
          	});

			$("#join_date").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,

              	onSelect: function(dateText, inst) {

              	}

          	});

			$("#baptism_date").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,

              	onSelect: function(dateText, inst) {

              	}

          	});
 
			$("#expired_date").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,

              	onSelect: function(dateText, inst) {

              	}
          	});
			
			//event status kaj
			console.log($(".rd_status_kaj:checked").val());
			console.log($(".rd_status_kaj [value='eu']").is(':checked'));
			if($(".rd_status_kaj:checked").val() == 'eu'){
				console.log('eu');
				$('#expired_date').attr("required",""); 
				$('#expired_date').show(); 
			}

			$('.rd_status_kaj').on('change', function(e){
				if(this.value == 'eu'){
					$('#expired_date').attr("required",""); 
					$('#expired_date').show();
				}else{
					$('#expired_date').removeAttr("required",""); 
					$('#expired_date').hide();
				}
			});

			//event babptism_status
			if($('.baptism_status').val() == 1){
				$('#baptism_date').attr("required",""); 
	        		$("#baptism_date").show();	  
			}
			$('.baptism_status').on('change', function(e){
	        	if(this.value == 1){
					$('#baptism_date').attr("required",""); 
	        		$("#baptism_date").show();	        		
	        	}else{
					$('#baptism_date').removeAttr("required",""); 
	        		$("#baptism_date").hide();
	        	}
		    });

			//event login_web
			if($(".login_web:checked").length > 0){
				$('.password_wrapper .password').attr("required",""); 
	        	$(".password_wrapper").show();	
			}

			$('.login_web').on('click', function(e){
				console.log($( ".login_web:checked" ));
	        	if($(".login_web:checked").length > 0){
					$('.password_wrapper .password').attr("required",""); 
	        		$(".password_wrapper").show();	        		
	        	}else{
					$('.password_wrapper .password').removeAttr("required",""); 
	        		$(".password_wrapper").hide();
	        	}
		    });
		});
	</script>
@endpush
@endsection