@if(isset($form_class))
<div class="{{isset($form_class) ? $form_class : ''}}">
@endif	
	<div class="form-group form-md-line-input">
		<label>{{isset($label) ? $label : ''}} <span class="required" aria-required="true">{{isset($required) ? ($required == 'y' ? '*' : '') : '*'}}</span></label>
		<select id="{{isset($id) ? $id : ''}}" class="form-control {{isset($class) ? $class : ''}}" name="{{isset($name) ? $name : ''}}" {{isset($attribute) ? $attribute : ''}} onchange="{{isset($onchange) ? $onchange : ''}}">
			<option value="" {{isset($value) ? ($value == '' ? 'selected' : '') : ''}}>--Select {{$label}}--</option>
			@if (count($data) > 0)
				@foreach($data as $d => $q)
					<option value={{$d}} {{$value == $d ? 'selected' : ''}}>{{$q}}</option>
				@endforeach
			@endif
		<select>
		<div class="form-control-focus"> </div>
		<small>{{isset($note) ? $note : ''}}</small>
	</div>
@if(isset($form_class))
</div>
@endif