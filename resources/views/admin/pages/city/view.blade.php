@extends($view_path.'.layouts.master')
@section('content')

@push('styles')
<style>

</style>

<form role="form" method="post" action="{{url($path)}}/{{$data->id}}" enctype="multipart/form-data">
{{ method_field('PUT') }}
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption font-green">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
      </div>
      <div class="actions">
        <a href="{{url($path)}}"><button type="button" class="btn red-mint">{{trans('general.back')}}</button></a>
      </div>
    </div>
    <div class="portlet-body form">
      @include('admin.includes.errors')
      <div class="row">        
        <div class="col-md-3">
              <ul class="list-unstyled profile-nav">
                  <li>
                      <img src="{{asset($image_path.$data->id.'/'.$data->images)}}" onerror="this.src='{{asset($image_path2.'/'.'none.png') }}';" alt="" class="img-responsive">
                  </li>
              </ul>
          </div>
          <div class="col-md-9">
              <div class="row">
                  <div class="col-md-8 profile-info">
                      <h1 class="font-green sbold uppercase">{{ $data->product_name ? $data->product_name : ' - ' }}</h1>
                      <p>
                          <i class="">Type</i> {{ $data->product_type_name ? $data->product_type_name : ' - ' }}
                      </p>
                      <p>
                          <i class="">Subcategory Product: </i> {{ $data->subcategory_product_name ? $data->subcategory_product_name : ' - ' }}
                      </p>
                      <p>
                          <i class="fa fa-map-marker"></i> {{ $data->product_type_name ? $data->product_type_name : ' - ' }}
                      </p>
                      <p>
                          <i class="fa fa-barcode"></i> {{ $data->barcode ? $data->barcode : ' - ' }}
                      </p>
                      <p>
                          <i class="fa fa-money"></i> {{ $data->price ? $data->price : ' - ' }}
                      </p>
                      <p>
                          <i class="fa fa-pencil"></i> {{ $data->description ? $data->description : ' - ' }}
                      </p>
                  </div>
              </div>
              
              <!--end row-->
          </div>
      </div>     
    </div>
    </div>
</form>
@endsection

@push('custom_scripts')
  <script>
    $(document).ready(function(){
      $('input,select,textarea,checkbox,.remove-single-image').prop('disabled',true);
      tinymce.settings = $.extend(tinymce.settings, { readonly: 1 });

       var price = $('.price');
        if(price.text() != '-'){
            console.log(price.text());
            price.text($.formatRupiah(price.text()));
        }
    });
  </script>
@endpush
