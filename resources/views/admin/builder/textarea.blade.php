@if(isset($form_class))
	<div class = "{{isset($form_class) ? $form_class : ''}}">
@endif
	<div class="form-group form-md-line-input" {{$rnd = str_random(3)}}>
		<textarea class="form-control {{isset($class) ? $class : ''}}" id="form_floating_{{$rnd}}" name="{{isset($name) ? $name : ''}}" {{isset($attribute) ? $attribute : ''}} placeholder="{{$label}}">{{$value}}</textarea>
		<label for="form_floating_{{$rnd}}">{{isset($label) ? $label : ''}} <span class="required" aria-required="true">{{isset($required) ? ($required == 'y' ? '*' : '') : ''}}</span></label>
		{{isset($note) ? '<span class="help-block">'.$note.'</span>span>' : ''}}</span>
	</div>
@if(isset($form_class))
	</div>
@endif