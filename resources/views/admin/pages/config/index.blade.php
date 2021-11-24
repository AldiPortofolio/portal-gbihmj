@extends($view_path.'.layouts.master')
@section('content')
<form role="form" method="post" action="{{url($path)}}/update" enctype="multipart/form-data">
  <div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-green">
          <i class="icon-layers font-green title-icon"></i>
          <span class="caption-subject bold uppercase"> {{$title}}</span>
        </div>
        <div class="actions">
          <div class="actions">
            {!!view($view_path.'.builder.button',['type' => 'submit','label' => trans('general.submit')])!!}
          </div>
        </div>
    </div>
    <div class="portlet-body form">
      @include('admin.includes.errors')
      <div class="tabbable-line">
        <ul class="nav nav-tabs ">
          <li class="active">
            <a href="#settings" data-toggle="tab" aria-expanded="true">{{trans('general.web-settings')}}</a>
          </li> 
          <li>
            <a href="#mobile_config" data-toggle="tab" aria-expanded="true">Mobile Config</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="settings">
            <div class="row">
                {!!view($view_path.'.builder.text',['type' => 'email','name' => 'web_email','label' => 'Global Email','value' => (old('web_email') ? old('web_email') : $configs->web_email),'attribute' => 'required','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'web_name','label' => 'Web Name','value' => (old('web_name') ? old('web_name') : $configs->web_name),'attribute' => 'required','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.file',['name' => 'favicon','label' => 'Favicon','value' => $configs->favicon,'type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-6','note' => 'Note: File Must jpeg,png,jpg,gif | Best Resolution: 30 x 30 px','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.file',['name' => 'web_logo','label' => 'Web Logo','value' => $configs->web_logo,'type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-6','note' => 'Note: File Must jpeg,png,jpg,gif | Best Resolution: 138 x 44 px','form_class' => 'col-md-6'])!!}
              </div>
          </div>

          <div class="tab-pane" id="mobile_config">
            <div class="row">
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'whatsapp','label' => 'WhatsApp','value' => (old('whatsapp') ? old('whatsapp') : $configs->whatsapp),'attribute' => 'required','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'phone','label' => 'Phone','value' => (old('phone') ? old('phone') : $configs->phone),'attribute' => 'required','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'instagram','label' => 'Instagram','value' => (old('instagram') ? old('instagram') : $configs->instagram),'attribute' => 'required','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'youtube','label' => 'Youtube','value' => (old('youtube') ? old('youtube') : $configs->youtube),'attribute' => 'required','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'web_url','label' => 'Web URL','value' => (old('web_url') ? old('web_url') : $configs->web_url),'attribute' => 'required','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'terms_condition_url','label' => 'Terms Condition URL','value' => (old('terms_condition_url') ? old('terms_condition_url') : $configs->terms_condition_url),'attribute' => 'required','form_class' => 'col-md-6'])!!}
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'privacy_policy_url','label' => 'Privacy Policy URL','value' => (old('privacy_policy_url') ? old('privacy_policy_url') : $configs->privacy_policy_url),'attribute' => 'required','form_class' => 'col-md-6'])!!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@push('custom_scripts')
  @if ($role->view == 'n')
    <script>
      $(document).ready(function(){
        // $('input,select,textarea').prop('disabled',true);
      });
    </script>
  @endif
@endpush
@endsection
