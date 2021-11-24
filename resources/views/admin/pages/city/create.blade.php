@extends($view_path.'.layouts.master')
@section('content')

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
                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'No Kartu','value' => (old('name') ? old('name') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => ''])!!}

                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'price','label' => 'Price (Rp.)','value' => (old('price') ? old('price') : ''),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'price'])!!}

                <div class="col-md-6">
                    <div class="form-group" style=''>
                          <label for="tag">Product Type</label>
                          <select class="select2 product_type" name="product_type">
                            @foreach($product_type as $u)
                                <option value="{{$u->id}}" {{old('product_type') ? (in_array($u,[old('product_type')]) ? 'selected' : '') : ''}}>{{$u->product_type_name}}</option>
                            @endforeach
                          </select>
                    </div>
                </div>

                <!-- <div class="col-md-6">
                    <div class="form-group" style=''>
                          <label for="tag">Category Product</label>
                          <select class="select2 category_product" name="category_product">
                            @foreach($category_product as $u)
                                <option value="{{$u->id}}" {{old('category_product') ? (in_array($u,[old('category_product')]) ? 'selected' : '') : ''}}>{{$u->category_product_name}}</option>
                            @endforeach
                          </select>
                    </div>
                </div> -->

                <div class="col-md-6">
                    <div class="form-group" style=''>
                          <label for="tag">Subcategory Product</label>
                          <select class="select2 subcategory_product" name="subcategory_product">
                            @foreach($subcategory_product as $u)
                                <option value="{{$u->id}}" {{old('subcategory_product') ? (in_array($u,[old('subcategory_product')]) ? 'selected' : '') : ''}}>{{$u->subcategory_product_name}}</option>
                            @endforeach
                          </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group" style=''>
                          <label for="tag">Province</label>
                          <select class="select2" name="province">
                            @foreach($province as $u)
                                <option value="{{$u->id}}" {{old('province') ? (in_array($u,[old('province')]) ? 'selected' : '') : ''}}>{{$u->name}}</option>
                            @endforeach
                          </select>
                    </div>
                </div>

                {!!view($view_path.'.builder.text',['name' => 'no_barcode','label' => 'No Barcode','value' => (old('no_barcode') ? old('no_barcode') : ''),'class' => '','form_class' => 'col-md-6','attribute' => ''])!!}


                <div class="col-md-6">
                  <div class="form-group form-md-line-input">
                    <input type="text" id="date" class="form-control" name="date" value="" readonly="" placeholder="Valid To">
                    <label for="form_floating_Hqd">Valid date<span class="" aria-required="true">*</span></label>
                    <small></small>
                  </div>
                </div>

                <!-- <div class="form-group col-md-12">
                    <div class="md-checkbox">
                        <label>Count On Daily Report</label>
                        <input type="checkbox" id="checkbox_form_1" class="md-check daily_report" name="daily_report" value="y" >
                        <label for="checkbox_form_1">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                        </label>
                    </div>
                </div> -->

                {!!view($view_path.'.builder.textarea',['name' => 'description','label' => 'Description','value' => (old('description') ? old('description') : ''),'class' => '','form_class' => 'col-md-6','attribute' => 'required'])!!}

                <div class="form-group form-md-line-input col-md-12">
                    <label>Image</label><br>
                    <label class="btn green input-file-label-image">
                        <input type="file" class="form-control col-md-12 single-image" name="image"> Pilih File
                    </label>
                     
                     <button type="button" class="btn red-mint remove-single-image" data-id="single-image" data-name="image">Hapus</button>
                    <input type="hidden" name="remove-single-image-image" value="n">
                    <br>
                    <small>Note: File Must jpeg,png,jpg,gif | Max file size: 2Mb | Best Resolution: x px</small>

                    <div class="form-group single-image-image col-md-12">
                        <img src="{{asset($image_path2.'/'.'none.png')}}" class="img-responsive thumbnail single-image-thumbnail">
                    </div>
                </div>

                <div class="col-md-12 actions">
                    {!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'y'])!!}   
                </div>
            </div>
        </div>
    </div>
</form>
@push('custom_scripts')
    <script>
        $(document).ready(function(){
            $( ".price" ).blur(function() {  
                // alert('test');
                //number-format the user input
                var val = $(this).val();
                var val2 = parseFloat(val.replace(/,/g, ""))
                              .toFixed(2)
                              .toString()
                              .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(this).val(val2);             
            });

            $(".category_product").change(function(){

                $("#outlet").prop('disabled', true);
            });

            $("#date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy',
                yearRange: "0:+90",
                showButtonPanel: true,

                onSelect: function(dateText, inst) {

                }

            });
        });
    </script>
@endpush
@endsection