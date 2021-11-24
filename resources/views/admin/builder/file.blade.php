<div class="form-group form-md-line-input {{isset($form_class) ? $form_class : ''}}">
	<label>{{isset($label) ? $label : ''}} <span class="required" aria-required="true">{{isset($required) ? ($required == 'y' ? '*' : '') : '*'}}</span></label><br/>
	<label class="btn green input-file-label-{{$name}}">
		<input type="{{isset($type) ? $type : 'file'}}" class="form-control {{isset($class) ? $class : ''}} {{isset($upload_type) ? $upload_type : ''}}" name="{{isset($name) ? $name : ''}}" {{isset($attribute) ? $attribute : ''}} {{isset($crop) && $crop == 'y' ? 'data-crop=y' : ''}}> {{trans('general.choose_file')}}
		@if(isset($crop) && $crop == 'y')
			<input type="hidden" name="{{$name}}_crop">
		@endif
	</label>
	@if($upload_type == 'single-image')
		<button type="button" class="btn red-mint remove-single-image" data-id="{{$upload_type}}" data-name="{{$name}}">{{trans('general.remove')}}</button>
		<input type="hidden" name="remove-{{$upload_type}}-{{$name}}" value="n">
	@endif
	<br>
	<small>{{isset($note) ? $note : ''}}</small>
</div>
@if(isset($crop) && $crop == 'y')
	@if ($upload_type == 'single-image')
		<div class="modal fade bs-modal-lg" id="crop-modal-{{$name}}" tabindex="-1" role="dialog" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		          <h4 class="modal-title">Crop Image</h4>
		        </div>
		        <div class="modal-body">
		          <center>
		            <div class="waiting-crop"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
		            <div class="container-crop container-crop-{{$name}} hidden">
		              <img class="img-responsive image-crop-data-{{$name}}" alt="Picture">
		            </div>
		          </center>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn dark btn-outline cancel-crop" data-name="{{$name}}">cancel</button>
		          <button type="button" class="btn green save-crop" data-name="{{$name}}">Save changes</button>
		        </div>
		    </div>
		  </div>
		</div>
	@else
		<div class="modal fade bs-modal-lg" id="crop-modal-{{$name}}" tabindex="-1" role="dialog" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		          <h4 class="modal-title">Crop Image</h4>
		        </div>
		        <div class="modal-body">
		          <center>
		            <div class="waiting-crop"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
		            <div class="container-crop container-crop-{{$name}} hidden">
		              <img class="img-responsive image-crop-data-{{$name}}" alt="Picture">
		            </div>
		          </center>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn dark btn-outline cancel-multiple-crop" data-name="{{$name}}" >cancel</button>
		          <button type="button" class="btn green save-multiple-crop" data-name="{{$name}}"  {{isset($attribute) ? $attribute : ''}} {{isset($crop) && $crop == 'y' ? 'data-crop=y' : ''}}>Save changes</button>
		        </div>
		    </div>
		  </div>
		</div>
	@endif
@endif
@if ($upload_type == 'single-image')
	<div class="form-group {{$upload_type}}-{{$name}} {{isset($form_class) ? $form_class : ''}}">
		@if(isset($value) && $value != '')
			<img src="{{asset($file_opt['path'].$value)}}" class="img-responsive thumbnail single-image-thumbnail">
		@else
			<img src="{{asset('components/both/images/web/none.png')}}" class="img-responsive thumbnail single-image-thumbnail">
		@endif
	</div>
	<div class="clearfix"></div>
@endif
@if ($upload_type == 'multiple-image')
	<table class="table table-bordered {{$upload_type}}-{{$name}} {{isset($form_class) ? $form_class : ''}}">
		<th class="info">Image</th>
		<th class="info">Thumbnail</th>
		<th class="info">Status</th>
		<th class="info">Action</th>
		<tbody class="sortable tbody-{{$name}}">
		@if($value != '')
			@foreach($value as $v)
				<tr class='multiple-images'>
					<td><img src="{{asset($file_opt['path'].$v->$name)}}" width="100"><input type="hidden" name="{{$name}}_hidden_data[]" value="{{$v->id}}"></td>
					<td>
						<div class="form-group form-md-radios">
							<div class="md-radio-inline">
								<div class="md-radio"{{$rnd = str_random(5)}}>
									<input type="radio" class="md-radiobtn" id="radio_{{$rnd}}" name="{{$name}}_status_primary" {{$v->status_primary == 'y' ? 'checked' : ''}} value="{{$v->id}}">
									<label for="radio_{{$rnd}}">
										<span></span>
				                   		<span class="check"></span>
				                    	<span class="box"></span>
									</label>
								</div>
							</div>
						</div>							
					</td>
					<td>
						<div class="form-md-checkboxes {{isset($form_class) ? $form_class : ''}}">
							<div class="md-checkbox-inline">
								<div class="md-checkbox" <?php $rnd = str_random(10) ?>>
									<input type="checkbox" id="checkbox_form_{{$rnd}}" class="md-check" name="{{$name}}_status[{{$v->id}}]" {{$v->status == 'y' ? 'checked' : ''}} value="y"> 
									<label for="checkbox_form_{{$rnd}}">
										<span></span>
								        <span class="check"></span>
								        <span class="box"></span>
									</label>
								</div>
							</div>
						</div>
					</td>
					<td><button type='button' class='btn btn-primary remove-multiple-image' data-id='{{$v->id}}' data-name='{{$name}}' {{isset($attribute) ? $attribute : ''}}><i class='fa fa-trash'></i></button></td></tr>
				</tr>
			@endforeach
		@endif
		</tbody>
	</table>
	<div class="clearfix"></div>
@endif