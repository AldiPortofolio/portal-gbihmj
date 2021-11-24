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
	          <div class="col-md-12">
		            <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
		            <h4>Order Info</h4>
		            </div>
	              	<!-- <small>Note: Leave blank to use default value</small> -->
	          </div>
	        </div>

	  		<div class="row">
				<div class="col-md-6">
	                <div class="form-group" style=''>
	                      <label for="tag">Order Status</label>
	                      <select class="select2" name="order_status">
	                        @foreach($order_status as $u)
	                            <option value="{{$u->id}}" {{old('order_status') ? old('order_status') : ''}}>{{$u->order_status_name}}</option>
	                        @endforeach
	                      </select>
	                </div>
	            </div>

					<!-- {!!view($view_path.'.builder.text',['type' => 'text','name' => 'order_no','label' => 'Order No','value' => (old('order_no') ? old('order_no') : ''),'attribute' => 'required autofocus readonly','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}  -->

				<div class="col-md-6">
	                <div class="form-group" style=''>
	                      <label for="tag">Country</label>
	                      <select class="select2 multiple country" id="country" name="country[]" multiple="">
	                        @foreach($country as $u)
	                            <option value="{{$u->continent_name.'-'.$u->id}}">{{$u->country_name}}</option>
	                        @endforeach
	                      </select>
	                </div>
	            </div>

	            <div class="col-md-6">
	              <div class="form-group form-md-line-input">
	                <input type="text" id="date1" class="form-control" name="date_from" readonly="" placeholder="Go From Indonesia" value="">
	                <label for="form_floating_Hqd">Date From <span class="" aria-required="true">*</span></label>
	                <small></small>
	              </div>
	            </div>

	            <div class="col-md-6">
	              <div class="form-group form-md-line-input">
	                <input type="text" id="date2" class="form-control" name="date_to" readonly="" placeholder="Arrival In Indonesia" value="">
	                <label for="form_floating_Hqd">Date To <span class="" aria-required="true">*</span></label>
	                <small></small>
	              </div>
	            </div>

	            {!!view($view_path.'.builder.text',['type' => 'number','name' => 'qty_modem','label' => 'Qty Modem','value' => (old('qty_modem') ? old('qty_modem') : 1),'attribute' => 'required autofocus min=1','form_class' => 'col-md-6', 'class' => 'name qty', 'required' => 'y'])!!}

	            <input type="hidden" class="weight" value="{{$weight}}">
  				
			</div>

			 <div class="row">
				<div class="col-md-12">
				    <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
				    <h4>Modem Info</h4>
				    </div>
				  	<!-- <small>Note: Leave blank to use default value</small> -->
				</div>
	        </div>

	        <button type="button" class="btn red-mint generate-modem" data-id="single-image" data-name="image">Generate Modem</button>

	        <div class="row">	
	        	<div class="col-md-6">
	                <div class="form-group" style=''>
	                      <label for="tag">Modem</label>
	                      <select class="modem" name="modem[]">
	                       <!--  @foreach($modem as $u)
	                            <option value="{{$u->id}}">{{$u->modem_name.' - '.$u->serial_no}}</option>
	                        @endforeach -->
	                      </select>
	                </div>
	            </div>
	        </div>

			<div class="row">
				<div class="col-md-12">
				    <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
				    <h4>Delivery Info</h4>
				    </div>
				  	<!-- <small>Note: Leave blank to use default value</small> -->
				</div>
	        </div>

	  		<div class="row">
	  			<div class="col-md-4">
		  			<div class="form-group form-md-radios">
						<label for="form_control">Pick Method</label>
						<div class="md-radio-inline" 0="">
							<input type="hidden" class="pm" value="">
				  			@foreach($delivery_method as $da)
								<?php $rnd = str_random(10) ?>
								<div class="md-radio">
									<input type="radio" id="checkbox_{{$da->id}}_{{$rnd}}" class="md-radiobtn rdo" name="delivery_method" value="{{$da->id}}" onclick="">
									<label for="checkbox_{{$da->id}}_{{$rnd}}">
										<span class="inc"></span>
				                   		<span class="check"></span>
				                    	<span class="box"></span>
										{{$da->delivery_method_name}}
									</label>
								</div>
								<br>
							@endforeach
							<small></small>
						</div>
					</div>
				</div>

				<div class="col-md-4">
	                <div class="form-group da" id="da" style=''>
	                      <label for="tag">Pick Address</label>
	                      <br>
	                      <select class="delivery_area" name="delivery_area" id="delivery_area">
	                      	<option value="">-- Please Select Pick Area--</option>
	                        @foreach($delivery_area as $u)
	                            <option value="{{$u->id}}" id="dm-{{$u->id}}">{{$u->description}}</option>
	                        @endforeach
	                      </select>
	                </div>

	                <div class="form-group da2" id="da2" style=''>
	                	 <label for="tag">Pick Address</label>
	                      <br>
	                      <input type="text" name="da2_address" id="da2_address" placeholder="Full Address" value="">
	                      <br>
	                      <br>
	                      <input type="text" name="da2_kodepos" id="da2_kodepos" placeholder="Post Code" value="">
	                      <br>
	                      <br>
	                       <select class="da2_province" name="da2_province" id="da2_province">
	                      	<option value="">-- Please Select Province --</option>
	                        @foreach($province as $u)
	                            <option value="{{$u->province_id}}" id="da2_province_{{$u->province_id}}">{{$u->province}}</option>
	                        @endforeach
	                      </select>
	                      <br>
	                      <br>
	                      <select class="da2_city" name="da2_city" id="da2_city">
	                      	<option value="">-- Please Select City--</option>
	                        @foreach($city as $key => $u)
	                            <option value="{{$u->city_id}}" id="da2_city_{{$u->city_id}}" class="da2_cities da2_city_province_{{$u->province_id}}">{{$u->city_name.' '.$u->type}}</option>
	                        @endforeach
	                      </select>
	                      <br>
	                      <br>
	                      <a><button type="button" class="btn red-mint check_ongkir">Check Ongkir</button></a>
	                      <br>
	                      <br>
	                      <span class="service_ongkir">{{$service_ongkir}}</span>
	                       <br>
	                       <br>
	                      <input type="text" name="da2_cost_ongkir" id="da2_cost_ongkir" class="da2_cost_ongkir" placeholder="Cost Ongkir" value="">
	                </div>
	            </div>
	  		</div>

	  		<div class="row">
	  			<div class="col-md-4">
		  			<div class="form-group form-md-radios">
						<label for="form_control">Return Method</label>
						<div class="md-radio-inline" 0="">
							<input type="hidden" class="rm" value="">
				  			@foreach($delivery_method2 as $da)
								<?php $rnd = str_random(10) ?>
								<div class="md-radio">
									<input type="radio" id="checkbox_{{$da->id}}_{{$rnd}}" class="md-radiobtn rdo2" name="return_method" {{$da->id}}="" value="{{$da->id}}" onclick="">
									<label for="checkbox_{{$da->id}}_{{$rnd}}">
										<span class="inc"></span>
				                   		<span class="check"></span>
				                    	<span class="box"></span>
										{{$da->delivery_method_name}}
									</label>
								</div>
								<br>
							@endforeach
							<small></small>
						</div>
					</div>
				</div>

				<div class="col-md-4">
	                <div class="form-group" style='' id='ra'>
	                      <label for="tag">Return Address</label>
	                      <br>
	                      <select class="return_area" name="return_area" id="return_area">
	                      	<option value="">-- Please Select Delivery Area--</option>
	                        @foreach($delivery_area as $u)
	                            <option value="{{$u->id}}" id="dm-{{$u->id}}">{{$u->description}}</option>
	                        @endforeach
	                      </select>
	                </div>

	                <div class="form-group same_address" style="display: none;">
					  	<div class="md-checkbox">
					  		<label>Same as delivery address</label>
							<input type="checkbox" id="checkbox_form_1" class="md-check login_web checkbox_same_address" name="checkbox_same_address" value="y" checked>
							<label for="checkbox_form_1">
								<span class="inc"></span>
						        <span class="check"></span>
						        <span class="box"></span>
							</label>
						</div>
					</div>

		            <div class="form-group" id="ra2" style=''>
		            	<label for="tag">Return Address</label>
	                      <br>
	                      <input type="text" name="ra2_address" id="ra2_address" placeholder="Full Address" value="">
	                      <br>
	                      <br>
	                      <input type="text" name="ra2_kodepos" id="ra2_kodepos" placeholder="Post Code" value="">
	                      <br>
	                      <br>
	                       <select class="ra2_province" name="ra2_province" id="ra2_province">
	                      	<option value="">-- Please Select Province--</option>
	                        @foreach($province as $u)
	                            <option value="{{$u->province_id}}" id="ra2_province_{{$u->province_id}}">{{$u->province}}</option>
	                        @endforeach
	                      	</select>
	                      <br>
	                      <br>
	                      <select class="ra2_city" name="ra2_city" id="ra2_city">
	                      	<option value="">-- Please Select City --</option>
	                        @foreach($city as $u)
	                            <option value="{{$u->city_id}}" id="ra2_city_{{$u->city_id}} ra2_city_province_{{$u->province_id}}" class="ra2_cities  ra2_city_province_{{$u->province_id}}">{{$u->city_name.' '.$u->type}}</option>
	                        @endforeach
	                      </select>
	                      <br>
	                      <br>
	                      <a><button type="button" class="btn red-mint check_return_ongkir">Check Ongkir</button></a>
	                      <br>
	                      <br>
	                      <span class="return_ongkir">{{$return_ongkir}}</span>
	                      <br>
	                      <br>
	                      <input type="text" name="ra2_cost_ongkir" id="ra2_cost_ongkir" class="ra2_cost_ongkir" placeholder="Return Ongkir" value="">
	                </div>
	            </div>

	  		</div>

	  		<div class="row">
				<div class="col-md-12">
				    <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
				    <h4>Customer Info</h4>
				    </div>
				  	<!-- <small>Note: Leave blank to use default value</small> -->
				</div>
	        </div>

	        <div class="row">
	        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'full_name','label' => 'Full Name','value' => (old('full_name') ? old('full_name') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

	        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'telephone','label' => 'Telephone','value' => (old('telephone') ? old('telephone') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

	        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'email','label' => 'Email','value' => (old('email') ? old('email') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

	        </div>

	        <div class="row">
				<div class="col-md-12">
				    <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
				    <h4>Bank Info</h4>
				    </div>
				  	<!-- <small>Note: Leave blank to use default value</small> -->
				</div>
	        </div>

	        <div class="row">
	        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'bank_name','label' => 'Bank Name','value' => (old('full_name') ? old('full_name') : ''),'attribute' => 'autofocus','form_class' => 'col-md-6', 'class' => 'name'])!!}

	        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'bank_account_name','label' => 'Bank Account Name','value' => (old('bank_account_name') ? old('bank_account_name') : ''),'attribute' => 'autofocus','form_class' => 'col-md-6', 'class' => 'name'])!!}

	        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'bank_account_no','label' => 'Bank Account No','value' => (old('bank_account_no') ? old('bank_account_no') : ''),'attribute' => 'autofocus','form_class' => 'col-md-6', 'class' => 'name'])!!}

	        	{!!view($view_path.'.builder.text',['type' => 'textarea','name' => 'passport_no','label' => 'Note','value' => (old('passport_no') ? old('passport_no') : ''),'attribute' => 'autofocus','form_class' => 'col-md-6', 'class' => 'name'])!!}

	        	<div class="form-group form-md-line-input col-md-12">
                    <label>Transfer Foto</label><br>
                    <label class="btn green input-file-label-image">
                        <input type="file" class="form-control col-md-12 single-image" name="image"> Pilih File
                    </label>
                     
                     <button type="button" class="btn red-mint remove-single-image" data-id="single-image" data-name="image">Hapus</button>
                    <!-- <input type="hidden" name="remove-single-image-image" value="n"> -->
                    <br>
                    <small>Note: File Must jpeg,png,jpg,gif | Max file size: 2Mb | Best Resolution: x px</small>

                    <div class="form-group single-image-image col-md-12">
                        <img src="" onerror="this.src='{{ asset($image_path2.'/'.'none.png') }}';" class="img-responsive thumbnail single-image-thumbnail">
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
			// alert('order');
			var password = $(".password");
			var username = $(".username");
			var name = $(".name");
			// var data = [
			// 		    {
			// 		        id: 0,
			// 		        text: 'enhancement'
			// 		    },
			// 		    {
			// 		        id: 1,
			// 		        text: 'bug'
			// 		    },
			// 		    {
			// 		        id: 2,
			// 		        text: 'duplicate'
			// 		    },
			// 		    {
			// 		        id: 3,
			// 		        text: 'invalid'
			// 		    },
			// 		    {
			// 		        id: 4,
			// 		        text: 'wontfix'
			// 		    }
			// 		];

			// 		$(".modem").select2({
			// 		  data: data,
			// 		  multiple: true
			// 		});

				// $('#delivery_method').children('option').prop('disabled', true);
				// var delivery_area_id = $('.rdo').val();

				// function getdeliveryMethod($id, optarray2, delivery_area_method, delivery_method_id = ''){

			 //        var addoptarr2 = [];
			 //        for (i = 0; i < optarray2.length; i++) {
			 //        	for(var j=0; j<delivery_area_method.length; j++){
				// 			if(delivery_area_method[j]['delivery_area_id'] == $id){
				// 				// console.log(delivery_area_method[j]['delivery_method_id'] +' - '+ optarray2[i].value);
				// 				if(delivery_area_method[j]['delivery_method_id'] == optarray2[i].value){
				// 					addoptarr2.push(optarray2[i].option);

				// 					if(optarray2[i].value == delivery_method_id){
				// 						$('.delivery_ongkir').val(delivery_area_method[j]['ongkir']);
				// 					}
				// 				}
			 //            	}
			 //            }
			 //        }
			 //        $("#delivery_method").html(addoptarr2.join(''));
					
				// }

				// function getreturnMethod($id, optarray2, delivery_area_method){

			 //        var addoptarr2 = [];
			 //        for (i = 0; i < optarray2.length; i++) {
			 //        	for(var j=0; j<delivery_area_method.length; j++){
				// 			if(delivery_area_method[j]['delivery_area_id'] == $id){
				// 				// console.log(delivery_area_method[j]['delivery_method_id'] +' - '+ optarray2[i].value);
				// 				if(delivery_area_method[j]['delivery_method_id'] == optarray2[i].value){
				// 					addoptarr2.push(optarray2[i].option);
				// 				}
			 //            	}
			 //            }
			 //        }
			 //        $("#return_method").html(addoptarr2.join(''));
					
				// }
				
				// var AreaId =  $(".rdo").val();

				// var optarray = $("#delivery_method").children('option').map(function() {
			 //        return {
			 //            "value": this.value,
			 //            "option": "<option value='" + this.value + "'>" + this.text + "</option>"
			 //        }
			 //    });

			 //    var AreaId2 =  $(".rdo2").val();

				// var rmoptarray = $("#return_method").children('option').map(function() {
			 //        return {
			 //            "value": this.value,
			 //            "option": "<option value='" + this.value + "'>" + this.text + "</option>"
			 //        }
			 //    });

				// var delivery_method_id = $('#delivery_method').val();
				// var delivery_area_method = JSON.parse($('#delivery_area_method').val());
				// $("#delivery_method").children('option').remove();			
				// getdeliveryMethod(AreaId, optarray, delivery_area_method, delivery_method_id);
				// $('#delivery_method').val(delivery_method_id);

				// var return_method_id = $('#return_method').val();
				// var return_area_method = JSON.parse($('#delivery_area_method').val());
				// $("#return_method").children('option').remove();			
				// getreturnMethod(AreaId2, rmoptarray, return_area_method);
				// $('#return_method').val(return_method_id);
				

			 //    $(".rdo").change(function() {
			 //        $("#delivery_method").children('option').remove();
			 //        var addoptarr = [];
			 //        for (i = 0; i < optarray.length; i++) {
			 //            for(var j=0; j<delivery_area_method.length; j++){
			 //            	if(delivery_area_method[j]['delivery_area_id'] == $(this).val()){
				// 				if(delivery_area_method[j]['delivery_method_id'] == optarray[i].value){
				//                 	addoptarr.push(optarray[i].option);
				//                 }
				//             }
			 //            }
			 //            // }
			 //        }
			 //        $("#delivery_method").html(addoptarr.join(''))
			 //    });

			 //    $('.delivery_method').change(function(){
			 //    	delivery_method_id = $('#delivery_method').val();
			 //    	AreaId 			  =  $(".rdo").val();
			 //    	for(var j=0; j<delivery_area_method.length; j++){
			 //    		if(delivery_area_method[j]['delivery_area_id'] == AreaId && delivery_area_method[j]['delivery_method_id'] == delivery_method_id){
				// 			$('.delivery_ongkir').val(delivery_area_method[j]['ongkir']);
				// 		}
				// 	}
			 //    });

				// $(".rdo2").change(function() {
				// 	console.log('test');
			 //        $("#return_method").children('option').remove();
			 //        var addoptarr = [];
			 //        for (i = 0; i < rmoptarray.length; i++) {
			 //            for(var j=0; j<return_area_method.length; j++){
			 //            	// console.log(return_area_method[j]['return_area_id'] +' -- '+ $(this).val());
			 //            	if(return_area_method[j]['delivery_area_id'] == $(this).val()){
			 //            		// console.log(return_area_method[j]['delivery_method_id'] +' - '+ rmoptarray[i].value);
				// 				if(return_area_method[j]['delivery_method_id'] == rmoptarray[i].value){
				//                 	addoptarr.push(rmoptarray[i].option);
				//                 }
				//             }
			 //            }
			 //            // }
			 //        }
			 //        $("#return_method").html(addoptarr.join(''))
			 //    });

			var rdo = $(".pm").val();
			var rdo2 = $(".rm").val();
			displayDa(rdo);
			displayRa(rdo2);
			$(".rdo").change(function() {
				id = $(this).val();

				displayDa(id);
			});

			function displayDa(id){
			 	$('.da').css('display', 'none');
			 	$('.da2').css('display', 'none');
			 	console.log(id);
				if(id == 2){
			 		$('.da2').css('display', '');
			 	}else if(id == 3){
			 		$('.da').css('display', '');
			 	}
			}

			  $(".rdo2").change(function() {
			 	id = $(this).val();
			 	 displayRa(id);
			 });

			$(".checkbox_same_address").click(function(e){
				if($(this).is(':checked')){
					$('#ra2').css('display', 'none');
				}else{
					$('#ra2').css('display', '');
				}
			});

			function displayRa(id){
			 	$('#ra').css('display', 'none');
			 	$('#ra2').css('display', 'none');
			 	console.log(id);
				if(id == 2){
			 		// $('#ra2').css('display', '');
			 		$('.same_address').css('display','');
			 	}else if(id == 5){
			 		$('#ra').css('display', '');
			 	}
			}
			
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

		    $("#date1").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,
		    	startDate: '-3d',

              	onSelect: function(dateText, inst) {

              	}

          	});

          	$("#date2").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,
		    	startDate: '-3d',

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

		    var province_id = $('#da2_province').val();
		    showDeliveryCityProvince(province_id);

		    var province_id = $('#ra2_province').val();
		    showReturnCityProvince(province_id);

		    $('.ra2_province').on('change', function(e){
		    	province_id = this.value;
		    	showReturnCityProvince(province_id);
		    });

		    $('.da2_province').on('change', function(e){
		    	province_id = this.value;
		    	showDeliveryCityProvince(province_id);
		    });

		    function showDeliveryCityProvince(province_id){
		    	$('.da2_cities').hide();
		    	$('.da2_city_province_'+province_id).show();
		    }

		    function showReturnCityProvince(province_id){
		    	$('.ra2_cities').hide();
		    	console.log('.ra2_city_province_'+province_id);
		    	$('.ra2_city_province_'+province_id).show();
		    }

		    $('.check_ongkir').on('click', function(e){
		    	var city_dest_id = $('.da2_city').val();
		    	// var city_dest_id = 5;
		    	$qty = $('.qty').val();
		    	$weight = $('.weight').val();
		    	$total = $qty * $weight;
		    	console.log($qty);
		    	console.log($weight);
		    	var url = $.root()+'order/manage-order/ext/getongkir?from=""&dest='+city_dest_id+'&weight='+$total;
		    	$.getdata(url).success(function(data){
		    		console.log(data);
		    		if(data){
		    			$('.service_ongkir').text(data['service_ongkir']);
		    			$('.da2_cost_ongkir').val(data['cost_ongkir']);
		    		}
		    	});
		    	
		    });

		    $('.check_return_ongkir').on('click', function(e){
		    	var city_dest_id = $('.ra2_city').val();
		    	// var city_dest_id = 5;
		    	$qty = $('.qty').val();
		    	$weight = $('.weight').val();
		    	$total = $qty * $weight;
		    	console.log($qty);
		    	console.log($weight);
		    	var url = $.root()+'order/manage-order/ext/getongkir?from=""&dest='+city_dest_id+'&weight='+$total;
		    	$.getdata(url).success(function(data){
		    		console.log(data);
		    		if(data){
		    			$('.return_ongkir').text(data['service_ongkir']);
		    			$('.ra2_cost_ongkir').val(data['cost_ongkir']);
		    		}
		    	});
		    	
		    });

		    $('.generate-modem').on('click', function(e){
		    	var searchCountry   = $("#country").select2('data');
		    	console.log(searchCountry);
		    	var arr 			= [];
		    	var date_from 		= $("#date1").val();
		    	var date_to 		= $("#date2").val();

		    	var returnval = 1;
		    	if(searchCountry.length == 0){
		    		returnval = 0;
		    		alert('Please select country');
		    	}else{
		    		for(var i in searchCountry){
		              	var getOption 	= searchCountry[i].id;
		    			// var ac 			= getOption.split('-');
						// var country 	= ac[1];
						// var continent 	= ac[0];
		              	// var option = getOption[0];

		              	arr.push(getOption);
		        	}

		    	}

		    	if(date_from == ""){
		    		returnval = 0;
		    		alert('Please input date from');
		    	}
		    	
		    	if(returnval == 1){
			       	var a 				= "../manage-order/ext/getModemAvailableByCountry";
			    	var files   		= {arr_country: arr, date_from: date_from, date_to: date_to};
			    	console.log(files);
			    	$.postdata(a, files).done(function(data){
			    		console.log(data);
			    		var res = JSON.parse(data);
			    		var arr = [];
			    		for(var i=0; i<res.length; i++){
			    			if(res[i]['show'] == 1){
				    			var temp = {
				    				id: res[i]['id'],
							        text: res[i]['modem_name']+' - '+res[i]['serial_no']
				    			};
				    			arr.push(temp);
				    		}
			    		}
				  		//     	var data = [
						//     {
						//         id: 0,
						//         text: 'enhancement'
						//     },
						//     {
						//         id: 1,
						//         text: 'bug'
						//     },
						//     {
						//         id: 2,
						//         text: 'duplicate'
						//     },
						//     {
						//         id: 3,
						//         text: 'invalid'
						//     },
						//     {
						//         id: 4,
						//         text: 'wontfix'
						//     }
						// ];
						$(".modem").empty();

						$(".modem").select2({
						  data: arr,
						  multiple: true
						});
				    });
				}
		    });

		    $('.country, #date1').on('change', function(){
		    	$(".modem").empty();
		    });

		    $('.modem').on('change', function(){
		    	console.log($(this).attr('name'));
		    });

		});
	</script>
@endpush
@endsection