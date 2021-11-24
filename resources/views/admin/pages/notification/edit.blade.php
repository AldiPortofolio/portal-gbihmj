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
  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'notification_title','label' => 'Notificatoin Title','value' => (old('notification_title') ? old('notification_title') : $data->notification_title),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'type','label' => 'Type','value' => (old('type') ? old('type') : $user->type),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'required' => 'y'])!!}

				{!!view($view_path.'.builder.text',['type' => 'description','name' => 'description','label' => 'Email','value' => (old('description') ? old('description') : $data->description),'attribute' => 'required autofocus','form_class' => 'col-md-12'])!!}
				
			  	{!!view($view_path.'.builder.file',['name' => 'picture','label' => 'Picture','value' => $user->images,'type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif | Best Resolution: 138 x 44 px','form_class' => 'col-md-12', 'required' => 'n'])!!}

  				<input type="hidden" id="root-url" value="{{$path}}" />
			</div>	
			{!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'y'])!!}
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
		});
	</script>
@endpush
@endsection