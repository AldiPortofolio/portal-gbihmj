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
  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Name','value' => (old('name') ? old('name') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'ongkir','label' => 'Ongkir','value' => (old('ongkir') ? old('ongkir') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'price'])!!}


				{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'description','label' => 'Description','value' => (old('description') ? old('description') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-12'])!!}

	            <div class="col-md-6">
	                <div class="form-group" style=''>
	                      <label for="tag">City</label>
	                      <select class="select2 multiple" name="city[]" multiple="">
	                        @foreach($city as $u)
	                            <option value="{{$u->city_id}}" id="da2_city_{{$u->city_id}}" class="da2_cities" {{in_array($u->city_id, $city) ? 'selected' : ''}} {{in_array($u->city_id, $city_ongkir_detail_selected) ? 'disabled' : ''}}>{{$u->city_name.' '.$u->type}}</option>
	                        @endforeach
	                      </select>
	                </div>
	            </div>

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

          	$("#birth_date2").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,

              	onSelect: function(dateText, inst) {

              	}

          	});

          	$("#birth_date3").datepicker({
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

		    $( ".price" ).blur(function() {  
	            // alert('test');
	            //number-format the user input
	            var val = $(this).val();
	            var val2 = parseFloat(val.replace(/,/g, ""))
	                          .toFixed(2)
	                          .toString()
	                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	            $(this).val(val2);             
	        });

	        var price = $('.price');
	        if(price.val() != ''){
	          price.val($.formatRupiah(price.val()));
	        }

		});
	</script>
@endpush
@endsection