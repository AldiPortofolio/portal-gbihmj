<div class="fileinput {{$value ? 'fileinput-exists' : 'fileinput-new'}}" data-provides="fileinput">
    <div class="fileinput-new">
        <img src="{{Helper::asset('components/images/web/none.png')}}" alt="" width="180" class="{{isset($img_class) ? $img_class : ''}}" />
    </div>
    <div class="fileinput-preview fileinput-exists {{!isset($class) ? 'thumbnail' : $class}}">
        @if($value)
            @if(isset($fancybox) && $fancybox == 1)
                <a href="{{Helper::asset($path)}}/{{$value}}" class="fancybox-button" data-rel="fancybox-button">
            @endif
            <img src="{{Helper::asset($path)}}/{{$value}}" class="{{isset($img_class) ? $img_class : ''}}">
            @if(isset($fancybox) && $fancybox == 1)
                </a>
            @endif
        @endif
    </div>
    <div>
        <span class="btn blue btn-file margin-top-10">
            <span class="fileinput-new"> {{trans('general.select_image')}} </span>
            <span class="fileinput-exists"> {{trans('general.change')}} </span>
            <input type="file" name="{{$name}}">
            <input type="hidden" name="remove-image-{{$name}}" value="n">
        </span>
        <a href="javascript:;" class="btn red fileinput-exists margin-top-10" data-dismiss="fileinput"> {{trans('general.remove')}} </a>
    </div>
</div>