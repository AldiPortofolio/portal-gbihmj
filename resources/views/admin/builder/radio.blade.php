@if(isset($form_class))
	<div class = "{{isset($form_class) ? $form_class : ''}}">
@endif
	<div class="form-group form-md-radios">
		<label for="form_control">{{isset($label) ? $label : ''}}</label>
		<div class="md-radio-inline" {{$no = 0}}>
			@if (count($data) > 0)
				@foreach($data as $d => $q)
					<div class="md-radio" <?php $rnd = str_random(10) ?>>
						<input type="radio" id="checkbox_{{$d}}_{{$rnd}}" class="md-radiobtn" name="{{$name}}" {{$no++}} {{isset($value) && $value ? ($value == $d ? 'checked' : '') : ($no++ == 1 ? 'checked': 'a')}} value="{{$d}}" onclick="{{isset($onclick) ? $onclick : ''}}" {{isset($attribute) ? $attribute : ''}}>
						<label for="checkbox_{{$d}}_{{$rnd}}">
							<span></span>
	                   		<span class="check"></span>
	                    	<span class="box"></span>
							{{$q}}
						</label>
					</div>
				@endforeach
			@endif
			<small>{{isset($note) ? $note : ''}}</small>
		</div>
	</div>

	
@if(isset($form_class))
</div>
@endif