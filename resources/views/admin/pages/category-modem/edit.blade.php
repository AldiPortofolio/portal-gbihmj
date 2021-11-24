@extends($view_path.'.layouts.master')
@section('content')
<style>

</style>

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
          {!!view($view_path.'.builder.text',['type' => 'text','name' => 'name','label' => 'Category Product Name','value' => (old('name') ? old('name') : $data->category_modem_name),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'category_modem_name'])!!}

           {!!view($view_path.'.builder.text',['type' => 'text','name' => 'rent_price','label' => 'Rent Price','value' => (old('rent_price') ? old('rent_price') : $data->rent_price),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'price'])!!}

            {!!view($view_path.'.builder.text',['type' => 'text','name' => 'deposit_price','label' => 'Deposit Price','value' => (old('deposit_price') ? old('deposit_price') : $data->deposit_price),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'price2'])!!}

            {!!view($view_path.'.builder.text',['type' => 'text','name' => 'penalty_rent_price_per_day','label' => 'Penalty Rent Price Per Day','value' => (old('penalty_rent_price_per_day') ? old('penalty_rent_price_per_day') : $data->penalty_rent_per_day),'attribute' => 'required autofocus','form_class' => 'col-md-6', 'class' => 'price3'])!!}

          {!!view($view_path.'.builder.textarea',['name' => 'description','label' => 'Description','value' => (old('description') ? old('description') : $data->description),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'description'])!!}

         <div class="col-md-12 actions">
          {!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'y'])!!}
        </div>
    </div>
</form>

@push('scripts')

@endpush
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

        $( ".price2" ).blur(function() {  
            // alert('test');
            //number-format the user input
            var val = $(this).val();
            var val2 = parseFloat(val.replace(/,/g, ""))
                          .toFixed(2)
                          .toString()
                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $(this).val(val2);             
        });

         $( ".price3" ).blur(function() {  
            // alert('test');
            //number-format the user input
            var val = $(this).val();
            var val2 = parseFloat(val.replace(/,/g, ""))
                          .toFixed(2)
                          .toString()
                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $(this).val(val2);             
        });

        var price = $('.price');
        if(price.val() != ''){
          price.val($.formatRupiah(price.val()));
        }

        var price2 = $('.price2');
        if(price2.val() != ''){
          price2.val($.formatRupiah(price2.val()));
        }

        var price3 = $('.price3');
        if(price3.val() != ''){
          price3.val($.formatRupiah(price3.val()));
        }
    });
        
  </script>
@endpush
@endsection
