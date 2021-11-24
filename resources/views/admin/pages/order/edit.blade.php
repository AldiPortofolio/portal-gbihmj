@extends($view_path.'.layouts.master')
@push('css')
	<style>
	.select2-results .select2-disabled,  .select2-results__option[aria-disabled=true] { 
    	display: none;
    }

    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
	    border: 1px solid black;
	}

	.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
    	border-top: solid 1px black;
	}
	</style>
@endpush('css')
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
	          <div class="col-md-12">
		            <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
		            <h4>Order Info</h4>
		            </div>
	              	<!-- <small>Note: Leave blank to use default value</small> -->
	          </div>
	        </div>

      		<div class="tabbable-line">
		        <ul class="nav nav-tabs">
		          <li class="active">
		            <a href="#order" data-toggle="tab" aria-expanded="true">Order</a>
		          </li>
		          <li>
		            <a href="#log" data-toggle="tab" aria-expanded="false">Log</a>
		          </li>
		        </ul>
		        <div class="tab-content">
		          	<div class="tab-pane active" id="order">
	  					<div class="row">
							<div class="col-md-6">
				                <div class="form-group" style=''>
				                      <label for="tag">Order Status</label>
				                      <select class="select2 order_status" name="order_status">
				                        @foreach($order_status as $u)
				                            <option value="{{$u->id}}" {{old('order_status') ? ($u->id == old('order_status') ? 'selected' : '') : ($u->id == $data->order_status_id ? 'selected' : '')}}>{{$u->order_status_name}}</option>
				                        @endforeach
				                      </select>
				                </div>
				            </div>

				            <div class="col-md-6 total_refund_div" style="display: none;">
				              <div class="form-group form-md-line-input">
				                <input type="number" id="total_refund" class="form-control" name="total_refund" placeholder="Total Refund" value="{{$data->total_refund != 0 ? $data->total_refund : 0}}">
				                <label for="form_floating_Hqd">Total Refund<span class="" aria-required="true">*</span></label>
				                <small></small>
				              </div>
				            </div>

							{!!view($view_path.'.builder.text',['type' => 'text','name' => 'order_no','label' => 'Order No','value' => (old('order_no') ? old('order_no') : $data->order_no),'attribute' => 'required autofocus readonly','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!} 

							<div class="col-md-6">
				                <div class="form-group" style=''>
				                      <label for="tag">Country</label>
				                      <select class="select2 multiple country" id="country" name="country[]" multiple="" readonly="">
				                        @foreach($country as $u)
				                            <option value="{{$u->continent_name.'-'.$u->id}}" {{in_array($u->id, json_decode($data->country_id)) ? "selected" : ""}} {{in_array($u->id, json_decode($data->country_id)) ? "" : "disabled"}}>{{$u->country_name}}</option>
				                        @endforeach
				                      </select>
				                </div>
				            </div>

				            <div class="col-md-6">
				              <div class="form-group form-md-line-input">
				                <input type="text" id="date1" class="form-control" name="date_from" readonly="" placeholder="Go From Indonesia" value="{{$data->go_from_indonesia != null ? date_format(date_create($data->go_from_indonesia),'d-m-Y') : ''}}">
				                <label for="form_floating_Hqd">Date From<span class="" aria-required="true">*</span></label>
				                <small></small>
				              </div>
				            </div>

				            <div class="col-md-6">
				              <div class="form-group form-md-line-input">
				                <input type="text" id="date2" class="form-control" name="date_to" readonly="" placeholder="Arrival In Indonesia" value="{{$data->arrival_in_indonesia != null ? date_format(date_create($data->arrival_in_indonesia),'d-m-Y') : ''}}">
				                <label for="form_floating_Hqd">Date To<span class="" aria-required="true">*</span></label>
				                <small></small>
				              </div>
				            </div>

				            {!!view($view_path.'.builder.text',['type' => 'number','name' => 'qty_modem','label' => 'Qty Modem','value' => (old('qty_modem') ? old('qty_modem') : $data->qty_modem),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name qty', 'required' => 'y'])!!}

				            <input type="hidden" class="weight" value="{{$weight}}">
				  				
							<div class="col-md-12">
							    <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
							    <h4>Modem Info</h4>
							    </div>
							  	<!-- <small>Note: Leave blank to use default value</small> -->
							</div>

							<div class="col-md-12">
					        	<!-- <button type="button" class="btn red-mint generate-modem" data-id="single-image" data-name="image">Generate Modem</button> -->
					        </div>

				        	<div class="col-md-6">
				                <div class="form-group" style=''>
				                      <label for="tag">Modem</label>
				                      <select class="form-control select2 modem" name="modem[]" multiple disabled="">
				                      	@php
			                        		$modem_arr = [];
			                        		foreach($orderdt as $o){
			                        			array_push($modem_arr, $o->modem_id);
			                        		}
			                            @endphp
			                            @if(isset($modem))
					                        @foreach($modem as $u)
					                            <option value="{{$u->id}}" {{in_array($u->id, $modem_arr) ? "selected" : ($u->show == 0 ? "disabled" : "")}}>{{$u->modem_name.' - '.$u->serial_no}}</option>
					                        @endforeach
					                    @endif
				                      </select>
				                </div>
				            </div>

							<div class="col-md-12">
							    <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
							    <h4>Delivery Info</h4>
							    </div>
							  	<!-- <small>Note: Leave blank to use default value</small> -->
							</div>

					  		<!-- <div class="row">
					  			<div class="col-md-4">
						  			<div class="form-group form-md-radios">
										<label for="form_control">Pick Area</label>
										<div class="md-radio-inline" 0="">
											@foreach($delivery_area as $da)
												<?php $rnd = str_random(10) ?>
												<div class="md-radio">
													<input type="radio" id="checkbox_{{$da->id}}_{{$rnd}}" class="md-radiobtn rdo" name="delivery_area" {{$da->id}}="" value="{{$da->id}}" onclick="" {{$da->id == $data->delivery_area_id ? 'checked' : ''}}>
													<label for="checkbox_{{$da->id}}_{{$rnd}}">
														<span class="inc"></span>
								                   		<span class="check"></span>
								                    	<span class="box"></span>
														{{$da->delivery_area_name}}
													</label>
												</div>
												<br>
											@endforeach
											<small></small>
										</div>
										</div>
									</div>

								<div class="col-md-4">
					                <div class="form-group" style=''>
					                      <label for="tag">Pick Method</label>
					                      <br>
					                      <select class="delivery_method" name="delivery_method" id="delivery_method">
					                        @foreach($delivery_method as $u)
					                            <option value="{{$u->id}}" id="dm-{{$u->id}}" {{$u->id == $data->delivery_method_id ? 'selected' : ''}}>{{$u->delivery_method_name}}</option>
					                        @endforeach
					                      </select>
					                </div>
					            </div>

					            <div class="col-md-4">
									<div class="form-group form-md-line-input">
										<input type="text" id="form_floating" class="form-control delivery_ongkir" name="delivery_ongkir" value="" required="" autofocus="" placeholder="Delivery Ongkir" readonly="">
										<label for="form_floating_B7m">Delivery Ongkir <span class="required" aria-required="true">*</span></label>
										<small></small>
									</div>
								</div>
					        </div>
					        <div class="row">
					            <div class="col-md-4">
						  			<div class="form-group form-md-radios">
										<label for="form_control">Return Area</label>
										<div class="md-radio-inline" 0="">
											@foreach($delivery_area as $da)
												<?php $rnd = str_random(10) ?>
												<div class="md-radio">
													<input type="radio" id="checkbox_{{$da->id}}_{{$rnd}}" class="md-radiobtn2 rdo2" name="return_area" {{$da->id}}="" value="{{$da->id}}" onclick="" {{$da->id == $data->return_area_id ? 'checked' : ''}}>
													<label for="checkbox_{{$da->id}}_{{$rnd}}">
														<span class="inc"></span>
								                   		<span class="check"></span>
								                    	<span class="box"></span>
														{{$da->delivery_area_name}}
													</label>
												</div>
												<br>
											@endforeach
											<small></small>
										</div>
										</div>
									</div>

								<div class="col-md-4">
					                <div class="form-group" style=''>
					                      <label for="tag">Return Method</label>
					                      <br>
					                      <select class="return_method" name="return_method" id="return_method">
					                        @foreach($delivery_method as $u)
					                            <option value="{{$u->id}}" id="rm-{{$u->id}}" {{$u->id == $data->return_method_id ? 'selected' : ''}}>{{$u->delivery_method_name}}</option>
					                        @endforeach
					                      </select>
					                </div>
					            </div>

					  		</div> -->

					  		<div class="row" style="margin: 0;">
					  			<div class="col-md-4">
						  			<div class="form-group form-md-radios">
										<label for="form_control">Pick Method</label>
										<div class="md-radio-inline" 0="">
											<input type="hidden" class="pm" value="{{$data->delivery_method_id}}">
								  			@foreach($delivery_method as $da)
												<?php $rnd = str_random(10) ?>
												<div class="md-radio">
													<input type="radio" id="checkbox_{{$da->id}}_{{$rnd}}" class="md-radiobtn rdo" name="delivery_method" value="{{$da->id}}" onclick="" {{$da->id == $data->delivery_method_id ? 'checked' : ''}}>
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
					                            <option value="{{$u->id}}" id="dm-{{$u->id}}" {{$u->id == $data->delivery_area_id ? 'selected' : ''}}>{{$u->description}}</option>
					                        @endforeach
					                      </select>
					                </div>

					                <div class="form-group da2" id="da2" style=''>
					                	 <label for="tag">Pick Address</label>
					                      <br>
					                      <input type="text" name="da2_address" id="da2_address" placeholder="Full Address" value="{{(old('da2_address') ? old('da2_address') : $data->delivery_address)}}">
					                      <br>
					                      <br>
					                      <input type="text" name="da2_kodepos" id="da2_kodepos" placeholder="Post Code" value="{{(old('da2_kodepos') ? old('da2_kodepos') : $data->delivery_post_code)}}">
					                      <br>
					                      <br>
					                       <select class="da2_province" name="da2_province" id="da2_province">
					                      	<option value="">-- Please Select Province --</option>
					                        @foreach($province as $u)
					                            <option value="{{$u->province_id}}" id="da2_province_{{$u->province_id}}" {{$u->province_id == $data->delivery_province_id ? 'selected' : ''}}>{{$u->province}}</option>
					                        @endforeach
					                      </select>
					                      <br>
					                      <br>
					                      <select class="da2_city" name="da2_city" id="da2_city">
					                      	<option value="">-- Please Select City--</option>
					                        @foreach($city as $key => $u)
					                            <option value="{{$u->city_id}}" id="da2_city_{{$u->city_id}}" class="da2_cities da2_city_province_{{$u->province_id}}" {{$u->city_id == $data->delivery_city_id ? 'selected' : ''}}>{{$u->city_name.' '.$u->type}}</option>
					                        @endforeach
					                      </select>
					                      <br>
					                      <br>
					                      <a><button type="button" class="btn red-mint check_ongkir" disabled="">Check Ongkir</button></a>
					                      <br>
					                      <br>
					                      <span class="service_ongkir">{{$service_ongkir}}</span>
					                       <br>
					                       <br>
					                      <input type="text" name="da2_cost_ongkir" id="da2_cost_ongkir" class="da2_cost_ongkir" placeholder="Cost Ongkir" value="{{$cost_ongkir}}">
					                </div>
					            </div>
					        </div>
					  		
					  		<div class="row" style="margin: 0;">
					  			<div class="col-md-4">
						  			<div class="form-group form-md-radios">
										<label for="form_control">Return Method</label>
										<div class="md-radio-inline" 0="">
											<input type="hidden" class="rm" value="{{$data->return_method_id}}">
								  			@foreach($delivery_method2 as $da)
												<?php $rnd = str_random(10) ?>
												<div class="md-radio">
													<input type="radio" id="checkbox_{{$da->id}}_{{$rnd}}" class="md-radiobtn rdo2" name="return_method" {{$da->id}}="" value="{{$da->id}}" onclick="" {{$da->id == $data->return_method_id ? 'checked' : ''}}>
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
					                            <option value="{{$u->id}}" id="dm-{{$u->id}}" {{$u->id == $data->return_area_id ? 'selected' : ''}}>{{$u->description}}</option>
					                        @endforeach
					                      </select>
					                </div>

					                <!-- <div class="form-group same_address" style="display: none;">
									  	<div class="md-checkbox">
									  		<label>Same as delivery address</label>
											<input type="checkbox" id="checkbox_form_1" class="md-check login_web checkbox_same_address" name="checkbox_same_address" value="y" checked>
											<label for="checkbox_form_1">
												<span class="inc"></span>
										        <span class="check"></span>
										        <span class="box"></span>
											</label>
										</div>
									</div> -->

						            <div class="form-group" id="ra2" style=''>
						            	<label for="tag">Return Address</label>
					                      <br>
					                      <input type="text" name="ra2_address" id="ra2_address" placeholder="Full Address" value="{{(old('ra2_address') ? old('ra2_address') : $data->return_address)}}">
					                      <br>
					                      <br>
					                      <input type="text" name="ra2_kodepos" id="ra2_kodepos" placeholder="Post Code" value="{{(old('ra2_kodepos') ? old('ra2_kodepos') : $data->return_post_code)}}">
					                      <br>
					                      <br>
					                       <select class="ra2_province" name="ra2_province" id="ra2_province">
					                      	<option value="">-- Please Select Province--</option>
					                        @foreach($province as $u)
					                            <option value="{{$u->province_id}}" id="ra2_province_{{$u->province_id}}" {{$u->province_id == $data->return_province_id ? 'selected' : ''}}>{{$u->province}}</option>
					                        @endforeach
					                      	</select>
					                      <br>
					                      <br>
					                      <select class="ra2_city" name="ra2_city" id="ra2_city">
					                      	<option value="">-- Please Select City --</option>
					                        @foreach($city as $u)
					                            <option value="{{$u->city_id}}" id="ra2_city_{{$u->city_id}} ra2_city_province_{{$u->province_id}}" class="ra2_cities  ra2_city_province_{{$u->province_id}}" {{$u->city_id == $data->return_city_id ? 'selected' : ''}}>{{$u->city_name.' '.$u->type}}</option>
					                        @endforeach
					                      </select>
					                      <br>
					                      <br>
					                      <a><button type="button" class="btn red-mint check_return_ongkir" disabled="">Check Ongkir</button></a>
					                      <br>
					                      <br>
					                      <span class="return_ongkir">{{$service_return_ongkir}}</span>
					                       <br>
					                       <br>
					                      <input type="text" name="ra2_cost_ongkir" id="ra2_cost_ongkir" class="ra2_cost_ongkir" placeholder="Cost Ongkir" value="{{$cost_return_ongkir}}">
					                </div>
					            </div>
					        </div>

							<div class="col-md-12">
							    <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
							    <h4>Customer Info</h4>
							    </div>
							  	<!-- <small>Note: Leave blank to use default value</small> -->
							</div>

				        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'full_name','label' => 'Full Name','value' => (old('full_name') ? old('full_name') : $data->full_name),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

				        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'telephone','label' => 'Telephone','value' => (old('telephone') ? old('telephone') : $data->telephone),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

				        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'email','label' => 'Email','value' => (old('email') ? old('email') : $data->email),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}


							<div class="col-md-12">
							    <div class="form-group form-md-line-input" style="border-bottom: 1px solid #eef1f5;">
							    <h4>Bank Info</h4>
							    </div>
							  	<!-- <small>Note: Leave blank to use default value</small> -->
							</div>

					        
				        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'bank_name','label' => 'Bank Name','value' => (old('full_name') ? old('full_name') : $data->bank_name),'attribute' => 'autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'n'])!!}

				        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'bank_account_name','label' => 'Bank Account Name','value' => (old('bank_account_name') ? old('bank_account_name') : $data->bank_account_name),'attribute' => 'autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'n'])!!}

				        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'bank_account_no','label' => 'Bank Account No','value' => (old('bank_account_no') ? old('bank_account_no') : $data->bank_account_no),'attribute' => 'autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'n'])!!}

				        	{!!view($view_path.'.builder.text',['type' => 'text','name' => 'note','label' => 'Note','value' => (old('note') ? old('note') : $data->note),'attribute' => 'autofocus','form_class' => 'col-md-12', 'class' => 'name'])!!}

				        	<div class="form-group form-md-line-input col-md-12">
			                    <label>Transfer Foto</label><br>
			                    <label class="btn green input-file-label-image">
			                        <input type="file" class="form-control col-md-12 single-image" name="image"> Pilih File
			                    </label>
			                     
			                     <button type="button" class="btn red-mint remove-single-image" data-id="single-image" data-name="image">Hapus</button>
			                    <input type="hidden" name="remove-single-image-image" value="n">
			                    <br>
			                    <small>Note: File Must jpeg,png,jpg,gif | Max file size: 2Mb | Best Resolution: x px</small>

			                    <div class="form-group single-image-image col-md-12">
			                        <img src="{{asset($image_path.'/'.$data->transfer_foto)}}" onerror="this.src='{{ asset($image_path2.'/'.'none.png') }}';" class="img-responsive thumbnail single-image-thumbnail">
			                    </div>
			                </div>

							<div class="col-md-12">
								{!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'y'])!!}
							</div>
						</div>
					</div>
					<div class="tab-pane" id="log">
	  					<div class="row">
	  						<div class="col-md-12 overflow-x-scroll">
		  						<table class="table table-striped">
								    <thead>
								      <tr>
								   		<th>Country</th>
								   		<th>Modem</th>
								        <th>From</th>
								        <th>To</th>
								        <th>Delivery Date</th>
								        <th>Return Date</th>
								        <th>Transfer Foto</th>
								        <th>Address</th>
								        <th>Order Status</th>
								        <th>Bank Name</th>
								        <th>Bank Account Name</th>
								        <th>Bank Account No.</th>
								        <th>Pick Method</th>
								        <th>Pick Area</th>
								        <th>Pick City</th>
								        <th>Pick Province</th>
								        <th>Pick Post Code</th>
								        <th>Pick Address</th>
								        <th>Return Method</th>
								        <th>Return Area</th>
								        <th>Return City</th>
								        <th>Return Province</th>
								        <th>Return Post Code</th>
								        <th>Return Address</th>
								        <!-- <th>Weight (Gram)</th> -->
								        <th>Full Name</th>
								        <th>Telephone</th>
								        <th>Email</th>
								        <th>Note</th>
								        <th>Qty Modem</th>
								        <th>Created At</th>
								      	<th>Updated At</th>
								        <th>Updated By</th>
								      </tr>
								    </thead>
								    <tbody>
										@foreach($orderhd_log as $val)
											<tr>
												<td>
													@foreach($country as $cou)
							                            {{in_array($cou->id, json_decode($val->country_id)) ? $cou->country_name.";" : ""}}
							                        @endforeach
						                        </td>
						                        <td>
						                        	@if($val->modem_id != null)
							                        	@foreach($modem_all as $m)
								                            {{in_array($m->id, json_decode($val->modem_id)) ? $m->modem_name.";" : ""}}
								                        @endforeach
								                    @endif
						                        </td>
						                        <td>{{$val->go_from_indonesia}}</td>
						                        <td>{{$val->arrival_in_indonesia}}</td>
						                        <td>{{$val->delivery_date}}</td>
						                        <td>{{$val->return_date}}</td>
						                        <td>
								                    <img src="{{asset($image_path.$val->order_id.'/'.$val->transfer_foto)}}" onerror="this.src='{{ asset($image_path2.'/'.'none.png') }}';" class="img-responsive thumbnail single-image-thumbnail" style="width: 100px;">
								                </td>
								                <td>
								                	{{$val->delivery_address ? $val->delivery_address : '-'}}
								                </td>
								                <td>
								                	@foreach($order_status as $u)
							                            {{$u->id == $val->order_status_id ? $u->order_status_name : ''}}
							                        @endforeach
								                </td>
								                <td>{{$val->bank_name}}</td>
								                <td>{{$val->bank_account_name}}</td>
								                <td>{{$val->bank_account_no}}</td>
								                
								                <td>
								                	@foreach($delivery_method as $da)
								                		{{$da->id == $val->delivery_method_id ? $da->delivery_method_name : ''}}
								                	@endforeach
								                </td>
								                <td>
								                	@foreach($delivery_area as $u)
							                            {{$u->id == $val->delivery_area_id ? $u->description : ''}}
							                        @endforeach
								                </td>
								                <td>
								                @foreach($city as $u)
						                            {{$u->city_id == $val->delivery_city_id ? $u->city_name.' '.$u->type : ''}}
						                        @endforeach
						                       	</td>
						                        <td>
													@foreach($province as $u)
							                           {{$u->province_id == $val->delivery_province_id ? $u->province : ''}}
							                        @endforeach
						                    	</td>
						                    	 <td>
								                	{{$val->delivery_post_code ? $val->delivery_post_code : '-'}}
								                </td>	
						                    	<td>{{$val->delivery_address}}</td>
						                    	<!-- return -->

								                <td>
								                	@foreach($delivery_method2 as $da)
								                		{{$da->id == $val->return_method_id ? $da->delivery_method_name : ''}}
								                	@endforeach
								                </td>
								                <td>
								                	@foreach($delivery_area as $u)
							                            {{$u->id == $val->return_area_id ? $u->description : ''}}
							                        @endforeach
								                </td>
								                <td>
									                @foreach($city as $u)
							                            {{$u->city_id == $val->return_city_id ? $u->city_name.' '.$u->type : ''}}
							                        @endforeach
						                       	</td>
						                       	<td>
													@foreach($province as $u)
							                           {{$u->province_id == $val->return_province_id ? $u->province : ''}}
							                        @endforeach
						                    	</td>
								                <td>
								                	{{$val->return_post_code ? $val->return_post_code : '-'}}
								                </td>	
						                    	<td>
								                	{{$val->return_address ? $val->return_address : '-'}}
								                </td>
								                <td>
								                	{{$val->full_name ? $val->full_name : '-'}}
								                </td>	
								                <td>
								                	{{$val->telephone ? $val->telephone : '-'}}
								                </td>	
								                <td>
								                	{{$val->email ? $val->email : '-'}}
								                </td>
								                <td>
								                	{{$val->note ? $val->note : '-'}}
								                </td>
								                <td>
								                	{{$val->qty_modem ? $val->qty_modem : '-'}}
								                </td>	
								                <td>
								                	{{$val->created_at ? $val->created_at : '-'}}
								                </td>	
								                <td>
								                	{{$val->updated_at ? $val->updated_at : '-'}}
								                </td>	
								                <td>
								                	@foreach($user as $u)
							                       		{{$u->id == $val->upd_by ? $u->username : ''}}

							                        @endforeach
								                </td>			
					                    	</tr>
										@endforeach
								    </tbody>
								</table>
							</div>
	  					</div>
	  				</div>
				</div>
		</div>
	</div>
</form>
@push('custom_scripts')
	<script>
		$(document).ready(function(){
			var password = $(".password");
			var username = $(".username");
			var name = $(".name");
			var order_status_value = $('.order_status').val();

			 $('.rdo').attr('disabled','disabled');
			 $('.rdo2').attr('disabled','disabled');
			 var rdo = $(".pm").val();
			 var rdo2 = $(".rm").val();
			 displayDa(rdo);
			 displayRa(rdo2);
			 $(".rdo").change(function() {
			 	id = $(this).val();

			 	displayDa(id);
			 });

		function displayDa(id){
			$('.da .delivery_area').attr('disabled','disabled');
			$('#da2_address').attr('disabled','disabled');
			$('#da2_kodepos').attr('disabled','disabled');
			$('#da2_province').attr('disabled','disabled');
			$('#da2_city').attr('disabled','disabled');
			$('#check_ongkir').hide();
			$('#da2_cost_ongkir').attr('disabled','disabled');

		 	$('.da').css('display', 'none');
		 	$('.da2').css('display', 'none');
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

		function displayRa(id){
			$('.return_area').attr('disabled','disabled');
			$('#ra2_address').attr('disabled','disabled');
			$('#ra2_kodepos').attr('disabled','disabled');
			$('#ra2_province').attr('disabled','disabled');
			$('#ra2_city').attr('disabled','disabled');
		 	$('#ra').css('display', '');
		 	$('#ra2').css('display', '');
		 	$('#check_return_ongkir').hide();
			$('#ra2_cost_ongkir').attr('disabled','disabled');

			if(id == 2){
		 		$('#ra').css('display', 'none');
		 	}else if(id == 5){
		 		$('#ra2').css('display', 'none');
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
		    	console.log('check_return_ongkir');
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
		    	var arr 			= [];
		    	var date_from 		= $("#date1").val();
		    	var date_to 		= $("#date2").val();


		    	for(var i in searchCountry){
		              	var getOption 	= searchCountry[i].id;
		    //           	var ac 			= getOption.split('-');
						// var country 	= ac[1];
						// var continent 	= ac[0];
		              // var option = getOption[0];

		              arr.push(getOption);
		        }

		       	var a 				= "../ext/getModemAvailableByCountry";
		    	var files   		= {arr_country: arr, date_from: date_from, date_to: date_to};
		    	console.log(files);
		    	$.postdata(a, files).done(function(data){
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
					$(".modem").empty();

					$(".modem").select2({
					  data: arr,
					  multiple: true
					});
			    });
		    });

		    $('.country, #date1').on('change', function(){
		    	console.log('test');
		    	$(".modem").empty();
		    });

		    if(order_status_value == 4 || order_status_value == 5){
		    	$('.total_refund_div').show();
		    }

		    $('.order_status').on('change', function(e){
		    	var value = $(this).val();
		    	console.log(value);
		    	$('.total_refund_div').hide();
		    	if(value == 4 || value == 5){
		    		$('.total_refund_div').show();
		    	}
		    });
		});
	</script>
@endpush
@endsection