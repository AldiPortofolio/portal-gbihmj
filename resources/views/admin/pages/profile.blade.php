@extends($view_path.'.layouts.master')
@section('content')
<div class="portlet light bordered">
  <div class="portlet-title">
      <div class="caption font-green portlet-container">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
      </div>
  </div>
  <div class="portlet-body form">
    <form role="form" method="post" action="{{url('_admin/update_profile')}}" enctype="multipart/form-data">
      @include('admin.includes.errors')

      {!!view($view_path.'.builder.text',['type' => 'email','name' => 'email','label' => trans('general.email'),'value' => auth()->guard($guard)->user()->email])!!}

      {!!view($view_path.'.builder.text',['type' => 'password','name' => 'password','label' => 'Password'])!!}

      {!!view($view_path.'.builder.text',['name' => 'name','label' => trans('general.name'),'value' => old('name') ? old('name') : auth()->guard($guard)->user()->name,'attribute' => 'required'])!!}

      <!-- {!!view($view_path.'.builder.textarea',['type' => 'textarea','name' => 'description','label' => 'About Me','value' => (old('description') ? old('description') : auth()->guard($guard)->user()->description),'attribute' => 'required'])!!} -->

      {!!view($view_path.'.builder.file',['name' => 'images','label' => trans('general.your_picture'),'value' => auth()->guard($guard)->user()->images,'type' => 'file','file_opt' => ['path' => $path],'upload_type' => 'single-image','form_class' => 'col-md-6 pad-left','note' => trans('general.notes').': File Must jpeg,png,jpg,gif | Best Resolution: 650 x 440'])!!}

      <div id="upload-demo"></div>

    
      <div class="clearfix"></div>
        <hr/>
      {!!view($view_path.'.builder.radio',['name' => 'language_id','label' => trans('general.language'),'data' => $language,'value' => auth()->guard($guard)->user()->language_id, 'class' => 'rdo2'])!!}

      <div class="clearfix"></div>
      <div class="box-footer">
        {!!view($view_path.'.builder.button',['type' => 'submit','label' => trans('general.submit')])!!}  
      </div>
    </form>
  </div>
</div>

@push('custom_scripts')
  <script>
    $(document).ready(function(){
      // alert('test');
      //   $('.md-radiobtn').on('change', function() {
      //       alert(this);
      //   });
    });
  </script>
@endpush
@endsection
