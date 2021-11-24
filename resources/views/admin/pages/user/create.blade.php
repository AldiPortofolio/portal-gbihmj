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
  				{!!view($view_path.'.builder.select',['name' => 'user_access_id','label' => 'User Access','value' => (old('user_access_id') ? old('user_access_id') : '3'),'attribute' => 'required','form_class' => 'col-md-12 display_none', 'data' => $user_access, 'class' => 'select2 user_access', 'onchange' => ''])!!}

  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Name','value' => (old('name') ? old('name') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'name', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.text',['type' => 'number','name' => 'phone','label' => 'Phone','value' => (old('phone') ? old('phone') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.text',['type' => 'email','name' => 'email','label' => 'Email','value' => (old('email') ? old('email') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'required' => 'y'])!!}

				<div class="col-md-6" id="birth_date-col">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="birth_date" class="form-control" name="birth_date" value="" readonly="" placeholder="Valid To">
		            <label for="form_floating_Hqd">Birth Date <!--<span class="" aria-required="true">*</span>--></label>
		            <small></small>
		          </div>
		        </div>

				<div class="col-md-12">
					<div class="form-group form-md-radios">
						<label for="form_control">Gender</label>
						<div class="md-radio-inline" 0="">
							<div class="md-radio">
								<input type="radio" id="checkbox_male" class="md-radiobtn" name="gender" 0="" checked="" value="m" onclick="">
								<label for="checkbox_male">
									<span></span>
									<span class="check"></span>
									<span class="box"></span>
									Male
								</label>
							</div>
							<div class="md-radio">
								<input type="radio" id="checkbox_female" class="md-radiobtn" name="gender" 1="" value="f" onclick="">
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

				{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'address','label' => 'Address','value' => (old('address') ? old('address') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'required' => 'y'])!!}

	            <div class="form-group col-md-2" style="display: none;">
				  	<div class="md-checkbox">
				  		<label>Login Backend</label>
						<input type="checkbox" id="checkbox_form_1" class="md-check login_web" name="login_web" value="y" >
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
				      		<input type="password" class="form-control password" id="password" name="password" placeholder="Password">
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

				<!-- <div class="form-group col-md-2">
					<div class="md-checkbox">
				  		<label>Login App</label>
						<input type="checkbox" id="checkbox_form_2" class="md-check login_app" name="login_app"  value="y">
						<label for="checkbox_form_2">
							<span></span>
					        <span class="check"></span>
					        <span class="box"></span>
						</label>
					</div>
				</div> -->


			  	{!!view($view_path.'.builder.file',['name' => 'picture','label' => 'Picture','value' => '','type' => 'file','file_opt' => ['path' => 'components/admin/images/user/'],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'n'])!!}

  				<input type="hidden" id="root-url" value="{{$path}}" />

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'no_kaj','label' => 'No. KAJ','value' => (old('no_kaj') ? old('no_kaj') : ''),'attribute' => 'autofocus','form_class' => 'col-md-6'])!!}

				<div class="col-md-6" id="">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="join_date" class="form-control" name="join_date" value="" readonly="" placeholder="01-01-2021">
		            <label for="form_floating_Hqd">Join Date <!--<span class="" aria-required="true">*</span>--></label>
		            <small></small>
		          </div>
		        </div>
				
				<div class="col-md-12" id="">
					<div class="form-group form-md-line-input" style="display: inline-block;min-width: 200px;">
						<select class="form-control select2 baptism_status select2-hidden-accessible" name="baptism_status" required="" onchange="" tabindex="-1" aria-hidden="true">
							<option value="">--Select Babptism Status--</option>
							@if (count($baptism_status_arr) > 0)
								@foreach($baptism_status_arr as $d => $q)
									<option value={{$d}}>{{$q}}</option>
								@endforeach
							@endif
							<!-- <option value="0">TANPA PENEGUHAN BAPTIS HMJ</option>
							<option value="1">AKAN MENGIKUTI BAPTISAN BERIKUT</option>
							<option value="2">Baptism Date</option> -->
						</select>
						<label for="form_floating_Hqd">Baptism Status <span class="required" aria-required="true">*</span></label>
		            	<small></small>
					</div>

					<div class="" style="display: inline-block;">
						<input type="text" id="baptism_date" class="form-control readonly" name="baptism_date" value="" placeholder="01-01-2021" style="display: none;">
					</div>
				</div>

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'no_baptism','label' => ' No. Baptism','value' => (old('no_baptism') ? old('no_baptism') : ''),'attribute' => 'autofocus','form_class' => 'col-md-6'])!!}

				<div class="col-md-12">
					<div class="form-group form-md-radios">
						<label for="form_control">Status KAJ</label>
						<div class="md-radio-inline" 0="">
							<div class="md-radio">
								<input type="radio" id="checkbox_expired" class="rd_status_kaj" name="status_kaj" 0="" value="e" onclick="">
								<label for="checkbox_expired">
									<span></span>
									<span class="check"></span>
									<span class="box"></span>
									Expired
								</label>
							</div>
							
							
							<div class="md-radio">
								<input type="radio" id="checkbox_expired_until" class="rd_status_kaj" name="status_kaj" 1=""  checked="" value="eu" onclick="">
								<label for="checkbox_expired_until">
									<span></span>
									<span class="check"></span>
									<span class="box"></span>
									Expired Until
								</label>
							</div>

							<div class="md-radio">
								<input type="text" id="expired_date" class="form-control readonly" name="expired_date" value="" placeholder="01-01-2021" style="">
							</div>
							<small></small>
						</div>
					</div>
				</div>

				{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'information','label' => 'Information','value' => (old('information') ? old('information') : ''),'attribute' => 'autofocus','form_class' => 'col-md-12'])!!}
			</div>	
			{!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'n'])!!}
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
			
			//event status
			if($(".rd_status_kaj [value=1]").is(':checked')) {
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

			$(".readonly").keydown(function(e){
				e.preventDefault();
			});

          	// $('.user_access').on('change', function(e){
	        // 	if(this.value == 3){
	        // 		$("#birth_date-col").css('display', '');
	        // 		$("#marketing-col").css('display', '');
	        		
	        // 	}else{
	        // 		$("#birth_date-col").css('display', 'none');
	        // 		$("#marketing-col").css('display', 'none');
	        // 	}
		    // });

		    $('.name').keyup(function(){ 
		    	console.log('keyup');
		      	var slug = convertToUsername($(this).val());
		      	$(".username").val(slug);    
		    });

			//event babptism_status
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