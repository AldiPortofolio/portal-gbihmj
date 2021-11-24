@extends($view_path.'.layouts.master')
@push('css')
	<style>
		.tooltip {
		    position: relative;
		    display: inline-block;
		    border-bottom: 1px dotted black;
		    opacity: 1;
		    font-size: 14px;
		}

		.tooltip .tooltiptext {
		    visibility: hidden;
		    width: 120px;
		    background-color: black;
		    color: #fff;
		    text-align: center;
		    border-radius: 6px;
		    padding: 5px 0;
		    position: absolute;
		    z-index: 1;
		    top: -5px;
		    right: 110%;
		}

		.tooltip .tooltiptext::after {
		    content: "";
		    position: absolute;
		    top: 50%;
		    left: 100%;
		    margin-top: -5px;
		    border-width: 5px;
		    border-style: solid;
		    border-color: transparent transparent transparent black;
		}
		.tooltip:hover .tooltiptext {
		    visibility: visible;
		}
		.clearfix{
			overflow: auto;
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
		            <a href="#modem" data-toggle="tab" aria-expanded="true">Modem</a>
		          </li>
		          <li>
		            <a href="#log" data-toggle="tab" aria-expanded="false">Log</a>
		          </li>
		         <!--  <li>
		            <a href="#activation" data-toggle="tab" aria-expanded="false">Activation</a>
		          </li> -->
		          <li>
		            <a href="#complain" data-toggle="tab" aria-expanded="false">Complain</a>
		          </li>
		        </ul>
		        <div class="tab-content">
		          	<div class="tab-pane active" id="modem">
	  					<div class="row">
							<div class="col-md-6">
					                <div class="form-group" style=''>
					                      <label for="tag">Category Modem</label>
					                      <select class="select2" name="category_modem">
					                        @foreach($category_modem as $u)
					                            <option value="{{$u->id}}" {{old('category_modem') ? ($u->id == old('category_modem') ? 'selected' : '') : ($u->id == $data->category_modem_id ? 'selected' : '')}}>{{$u->category_modem_name}}</option>
					                        @endforeach
					                      </select>
					                </div>
			            	</div>


			  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Name','value' => (old('name') ? old('name') : $data->modem_name),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

			  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'serial_no','label' => 'Serial No.','value' => (old('serial_no') ? old('serial_no') : $data->serial_no),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

			  				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'imei','label' => 'IMEI','value' => (old('imei') ? old('imei') : $data->imei),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

							{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'description','label' => 'Description','value' => (old('description') ? old('description') : $data->description),'attribute' => 'required autofocus','form_class' => 'col-md-6'])!!}

						  	<div class="col-md-6">
				                <div class="form-group" style=''>
				                      <label for="tag">Modem Status</label>
				                      <select class="select2" name="modem_status">
				                        @foreach($modem_status as $u)
				                            <option value="{{$u->id}}" {{old('modem_status') ? ($u->id == old('modem_status') ? 'selected' : '') : ($u->id == $data->modem_status_id ? 'selected' : '')}}>{{$u->modem_status_name}}</option>
				                        @endforeach
				                      </select>
				                </div>
				            </div>

				            <div class="col-md-6">
					          <div class="form-group form-md-line-input">
					            <input type="text" id="birth_date" class="form-control" name="date_of_entry" readonly="" placeholder="Date of Entry" value="{{$data->date_of_entry != null ? date_format(date_create($data->date_of_entry),'d-m-Y') : ''}}">
					            <label for="form_floating_Hqd"> Date of Entry<span class="" aria-required="true">*</span></label>
					            <small></small>
					          </div>
					        </div>

					         <div class="col-md-6">
					          <div class="form-group form-md-line-input">
					            <input type="text" id="" class="form-control datetimepicker" name="activation_date" value="{{$data->activation_date != null ? date_format(date_create($data->activation_date),'d-m-Y H:i:s') : ''}}" readonly="" placeholder="Activation Date">
					            <label for="form_floating_Hqd"> Activation Date<span class="" aria-required="true">*</span></label>
					            <small></small>
					          </div>
					        </div>

					        <div class="col-md-6">
					          <div class="form-group form-md-line-input">
					            <input type="text" id="" class="form-control datetimepicker" name="deactivation_date" value="{{$data->deactivation_date != null ? date_format(date_create($data->deactivation_date),'d-m-Y H:i:s') : ''}}" readonly="" placeholder="Deactivation Date">
					            <label for="form_floating_Hqd"> Deactivation Date<span class="" aria-required="true">*</span></label>
					            <small></small>
					          </div>
					        </div>

					        {!!view($view_path.'.builder.text',['type' => 'text','name' => 'password','label' => 'Password','value' => (old('password') ? old('password') : $data->password),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

					        {!!view($view_path.'.builder.text',['type' => 'text','name' => 'weight','label' => 'Weight (Gram)','value' => (old('weight') ? old('weight') : $data->weight_gram),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'name', 'required' => 'y'])!!}

					        {!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'complain','label' => 'Complain','value' => (old('complain') ? old('complain') : $data->complain),'attribute' => 'autofocus','form_class' => 'col-md-6'])!!}

					        {!!view($view_path.'.builder.file',['name' => 'image','label' => 'Image','value' => $data->image,'type' => 'file','file_opt' => ['path' => $image_path.$data->id.'/'],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif | Best Resolution: 138 x 44 px','form_class' => 'col-md-12', 'required' => 'n'])!!}

						</div>
					</div>
					<div class="tab-pane" id="log">
	  					<div class="row">
	  						<div class="col-md-12 overflow-x-scroll">
		  						<table class="table table-striped">
								    <thead>
								      <tr>
								   		<th>Created At</th>
								        <th>Modem Name</th>
								        <th>Serial No</th>
								        <th>IMEI</th>
								        <th>Category Modem id</th>
								        <th>Modem Status</th>
								        <th>Date Of Entry</th>
								        <!-- <th>Weight (Gram)</th> -->
								        <th>Description</th>
								        <th>Activation Date</th>
								      	<th>Deactivation Date</th>
								        <th>Update By</th>
								      </tr>
								    </thead>
								    <tbody>
								    	@foreach($modem_log as $v)
									      <tr>
									    	<td>{{$v->created_at}}</td>
									        <td>{{$v->modem_name}}</td>
									        <td>{{$v->serial_no}}</td>
									        <td>{{$v->imei}}</td>
									        <td>{{$v->category_modem_name}}</td>
									        <td>{{$v->modem_status_name}}</td>
									        <td>{{$v->date_of_entry}}</td>
									        <!-- <td>{{$v->weight_gram}}</td> -->
									        <td>
									        	<a type="button" class="" data-toggle="modal" data-target="#descModal-{{$v->id}}">{{substr($v->description,0,50)}}</a>
									        </td>
									        <td>{{date_format(date_create($v->activation_date),'d-m-Y')}}</td>
									      	<td>{{date_format(date_create($v->deactivation_date),'d-m-Y')}}</td>
									        <td>{{$v->username}}</td>
									        
									      </tr>

									      	<!-- Modal -->
											<div id="descModal-{{$v->id}}" class="modal fade" role="dialog">
											  <div class="modal-dialog">

											    <!-- Modal content-->
											    <div class="modal-content">
											      <div class="modal-header">
											        <button type="button" class="close" data-dismiss="modal">&times;</button>
											        <h4 class="modal-title">Description</h4>
											      </div>
											      <div class="modal-body clearfix">
											        <p>{{$v->description}}</p>
											      </div>
											      <div class="modal-footer">
											        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											      </div>
											    </div>

											  </div>
											</div>
								      	@endforeach
								    </tbody>
								</table>
							</div>
	  					</div>
	  				</div>
	  				<div class="tab-pane" id="activation">
	  					<div class="row">
	  						<div class="col-md-12 overflow-x-scroll">
		  						<table class="table table-striped">
								    <thead>
								      <tr>
								   		<th>Created At</th>
								        <th>Modem Name</th>
								        <th>Serial No</th>
								        <th>IMEI</th>
								        <th>Category Modem id</th>
								        <th>Modem Status</th>
								        <th>Date Of Entry</th>
								        <th>Description</th>
								        <th>Activation Date</th>
								      	<th>Deactivation Date</th>
								        <th>Update By</th>
								      </tr>
								    </thead>
								    <tbody>
								    	@foreach($modem_log as $v)
									      <tr>
									    	<td>{{$v->created_at}}</td>
									        <td>{{$v->modem_name}}</td>
									        <td>{{$v->serial_no}}</td>
									        <td>{{$v->imei}}</td>
									        <td>{{$v->category_modem_name}}</td>
									        <td>{{$v->modem_status_name}}</td>
									        <td>{{$v->date_of_entry}}</td>
									        <td>
									        	<a type="button" class="" data-toggle="modal" data-target="#descModal-{{$v->id}}">{{substr($v->description,0,50)}}</a>
									        </td>
									        <td>{{date_format(date_create($v->activation_date),'d-m-Y')}}</td>
									      	<td>{{date_format(date_create($v->deactivation_date),'d-m-Y')}}</td>
									        <td>{{$v->username}}</td>
									        
									      </tr>

									      	<!-- Modal -->
											<div id="descModal-{{$v->id}}" class="modal fade" role="dialog">
											  <div class="modal-dialog">

											    <!-- Modal content-->
											    <div class="modal-content">
											      <div class="modal-header">
											        <button type="button" class="close" data-dismiss="modal">&times;</button>
											        <h4 class="modal-title">Description</h4>
											      </div>
											      <div class="modal-body clearfix">
											        <p>{{$v->description}}</p>
											      </div>
											      <div class="modal-footer">
											        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											      </div>
											    </div>

											  </div>
											</div>
								      	@endforeach
								    </tbody>
								</table>
							</div>
	  					</div>
	  				</div>
	  				<div class="tab-pane" id="complain">
	  					<div class="row">
	  						<div class="col-md-12 overflow-x-scroll">
		  						<table class="table table-striped">
								    <thead>
								      <tr>
								   		<th>Update At</th>
								        <th>Modem Name</th>
								        <th>Serial No</th>
								        <th>IMEI</th>
								        <th>Category Modem id</th>
								        <th>Complain</th>
								        <th>Update By</th>
								      </tr>
								    </thead>
								    <tbody>
								    	@foreach($modem_log as $v)
								    		@if($v->complain != null)
										      <tr>
										    	<td>{{$v->updated_at}}</td>
										        <td>{{$v->modem_name}}</td>
										        <td>{{$v->serial_no}}</td>
										        <td>{{$v->imei}}</td>
										        <td>{{$v->category_modem_name}}</td>
										        <td>
										        	<a type="button" class="" data-toggle="modal" data-target="#complainModal-{{$v->id}}">{{substr($v->complain,0,50)}}</a>
										        </td>
										        <td>{{$v->username}}</td>
										        
										      </tr>

										      	<!-- Modal -->
												<div id="complainModal-{{$v->id}}" class="modal fade" role="dialog">
												  <div class="modal-dialog">

												    <!-- Modal content-->
												    <div class="modal-content">
												      <div class="modal-header">
												        <button type="button" class="close" data-dismiss="modal">&times;</button>
												        <h4 class="modal-title">Complain</h4>
												      </div>
												      <div class="modal-body clearfix">
												        <p>{{$v->complain}}</p>
												      </div>
												      <div class="modal-footer">
												        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												      </div>
												    </div>

												  </div>
												</div>
											@endif
								      	@endforeach
								    </tbody>
								</table>
							</div>
	  					</div>
	  				</div>
				</div>
				<br>	
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

		    $(function () {
			 	$('[data-toggle="popover"]').popover()
			})
		});
	</script>
@endpush
@endsection