@extends($view_path.'.layouts.master')
@section('content')
@section('content')
<style>

</style>

@push('styles')
<style>

</style>

<form role="form" method="post" action="{{url($path)}}" enctype="multipart/form-data">
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
          {!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Subcategory Product Name','value' => (old('subcategory_product_name') ? old('subcategory_product_name') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'subcategory_product_name'])!!}

          <div class="col-md-6">
              <div class="form-group" style=''>
                    <label for="tag">Category Product</label>
                    <select class="select2" name="category_product">
                      <!-- <option value="0">-- Please Select Category Product --</option> -->
                      @foreach($category_product as $u)
                          <option value="{{$u->id}}" {{old('category_product') ? (in_array($u,[old('category_product')]) ? 'selected' : '') : ''}}>{{$u->category_product_name}}</option>
                      @endforeach
                    </select>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
              <div class="form-group" style=''>
                    <label for="tag">Parent</label>
                    <select class="select2" name="parent">
                      <option value="0">-- Please Select Parent --</option>
                      @foreach($parent as $u)
                          <option value="{{$u->id}}" {{old('parent') ? (in_array($u,[old('parent')]) ? 'selected' : '') : ''}}>{{$u->subcategory_product_name}}</option>
                      @endforeach
                    </select>
              </div>
          </div>

          <div class="col-md-6">
              <div class="form-group" style=''>
                    <label for="tag">Operator</label>
                    <select class="select2" name="operator">
                      <!-- <option value="0">-- Please Select Category Product --</option> -->
                      @foreach($operator as $u)
                          <option value="{{$u->id}}" {{old('operator') ? (in_array($u,[old('operator')]) ? 'selected' : '') : ''}}>{{$u->operator_name}}</option>
                      @endforeach
                    </select>
              </div>
          </div>

          {!!view($view_path.'.builder.textarea',['name' => 'description','label' => 'Description','value' => (old('description') ? old('description') : ''),'attribute' => 'autofocus','form_class' => 'col-md-6', 'class' => 'description'])!!}

        <div class="col-md-12 actions">
          {!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'y'])!!}
        </div>
      </div>
</form>

@push('scripts')

@endpush
@push('custom_scripts')
  <script>
    // $(document).ready(function(){
        $('.title').keyup(function(){ 
          var slug = convertToSlug($(this).val());
          console.log(slug);
          $(".slug").val(slug);    
        });

        function convertToSlug(Text)
        {
            return Text
                .toLowerCase()
                .replace(/ /g,'-')
                .replace(/[^\w-]+/g,'')
                ;
        }

        getCity();
         $('.province').change(function(){
            getCity();
         });

        function getCity(){
            var id = $('.province').val();
            var arr_city = JSON.parse($('#arr_city').val());
            var text = '';
            for(var i=0; i<arr_city.length; i++){
                if(arr_city[i]['province_id'] == id){
                    text += '<option value="'+arr_city[i]['id']+'">'+arr_city[i]['name']+'</option>';
                }
            }
            // text += '';
            console.log(text);
            $('.city2 .select2-container--bootstrap .select2-selection--single .select2-selection__rendered').text('');
            $('.city').text('');
            $('.city').val('');
            // $('.city').prop('disabled', false);
            $('.city').append(text);
        }
    // });
  </script>
@endpush
@endsection
