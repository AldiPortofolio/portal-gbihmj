@if(isset($form_class))
<div class = "{{isset($form_class) ? $form_class : ''}}">
@endif
	<div class="form-group form-md-line-input" {{$rnd = str_random(3)}}>
		<input type="{{isset($type) ? $type : 'text'}}" id="form_floating_{{$rnd}}" class="form-control {{isset($class) ? $class : ''}}" name="{{isset($name) ? $name : ''}}" value="{{isset($value) ? $value : ''}}" {{isset($attribute) ? $attribute : ''}} placeholder="{{isset($placeholder) ? $placeholder : $label}}">
		<label for="form_floating_{{$rnd}}">{{isset($label) ? $label : ''}} <span class="required" aria-required="true">{{isset($required) ? ($required == 'y' ? '*' : '') : ''}}</span></label>
		<small>{!!isset($note) ? $note : ''!!}</small>
	</div>
@if(isset($form_class))
</div>
	@if(in_array('pad-right',explode(' ',$form_class)))
		<div class="clearfix"></div>
	@endif
@endif
