@extends($view_path.'.layouts.master')
@section('content')
<div class="portlet light bordered">
  <div class="portlet-title">
      <div class="caption font-green portlet-container">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
        <div class="head-button">
          {!!$head_button!!}
        </div>
      </div>
  </div>
  <div class="portlet-body form">
    {!!$content!!}
  </div>
</div>
@if(isset($scripts))
  @push('custom_scripts')
    <script>
      $(document).ready(function(){
        {!!$scripts!!}
      });
    </script>
  @endpush
@endif

@if(isset($current_path))
  @if($current_path == 'user/create')
    @push('scripts')
      <script>
        console.log('test');
      </script>
    @endpush
  @endif
@endif
@endsection
