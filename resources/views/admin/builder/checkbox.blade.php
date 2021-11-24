<div class="form-md-checkboxes {{isset($form_class) ? $form_class : ''}}">
	<div class="md-checkbox-inline">
		@if(isset($label))
			<label>{{$label}}</label>
		@endif
		<div class="clearfix"></div>
		@if (count($data) > 0)
			@foreach($data as $d => $q)
				<div class="md-checkbox" <?php $rnd = str_random(10) ?>>
					<input type="checkbox" id="checkbox_form_{{$rnd}}" class="md-check {{isset($class) ? $class : ''}}" name="{{$name}}[]" {{isset($value) ? (in_array($d,$value) ? 'checked' : '') : ''}} value="{{$d}}" {{isset($attribute) ? $attribute : ''}}> 
					<label for="checkbox_form_{{$rnd}}">
						{{$q}}
						<span></span>
				        <span class="check"></span>
				        <span class="box"></span>
					</label>
				</div>
			@endforeach
		@endif
		<div class="clearfix"></div>
		<small>{{isset($note) ? $note : ''}}</small>
	</div>
</div>
