@extends($view_path.'.layouts.master')
@section('content')

<form role="form" method="post" action="{{url($path)}}/{{$data->id}}" enctype="multipart/form-data">
    {{  method_field('PUT') }}
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
                <!-- <div class="col-md-6">
                    <div class="form-group" style=''>
                          <label for="tag">Province</label>
                          <select class="select2" name="province">
                            @foreach($province as $u)
                                <option value="{{$u->id}}" {{old('province') ? ($u->id == old('province') ? 'selected' : '') : ($data->province_id ? ($data->province_id == $u->id ? 'selected' : '') : '' )}}>{{$u->name}}</option>
                            @endforeach
                          </select>
                    </div>
                </div> -->

                <div class="col-md-6">
                    <div class="form-group" style=''>
                          <label for="tag">City</label>
                          <select class="select2" name="city">
                            @foreach($city as $u)
                                <option value="{{$u->id}}" {{old('city') ? ($u->id == old('city') ? 'selected' : '') : ($data->city_id ? ($data->city_id == $u->id ? 'selected' : '') : '' )}}>{{$u->name}}</option>
                            @endforeach
                          </select>
                    </div>
                </div>

                {!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'District Name','value' => (old('name') ? old('name') : $data->name),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => ''])!!}

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

            

            function parseRupiah(val){
                console.log(val);
                val = val.val();
                var val2 = parseFloat(val.replace(/,/g, ""))
                              .toFixed(2)
                              .toString()
                              .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                console.log(val2);
                // return val2;
                $(this).val(val2); 
            }

            var price = $('.price');
            if(price.val() != ''){
                price.val($.formatRupiah(price.val()));
            }
            // parseRupiah(val);

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