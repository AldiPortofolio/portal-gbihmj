@extends($view_path.'.layouts.master')
@section('content')
<form role="form" method="post" action="{{url($path)}}/{{$data->id}}" enctype="multipart/form-data">
  {{ method_field('PUT') }}
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption font-green portlet-container">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
        <div class="head-button">
          <a href="{{url($path)}}"><button type="button" class="btn red-mint"><i class="fa fa-arrow-left"></i> {{trans('general.back')}}</button></a>
          {!!view($view_path.'.builder.button',['type' => 'submit','label' => 'Submit'])!!}
        </div>
      </div>
    </div>
    <div class="portlet-body form">
      @include('admin.includes.errors')
      <div class="tabbable-line">
        <ul class="nav nav-tabs ">
          <li class="active">
            <a href="#summary" data-toggle="tab" aria-expanded="true">Information</a>
          </li>
          <li>
            <a href="#condition" data-toggle="tab" aria-expanded="false">Condition</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="summary">
            <div class="row">
              {!!view($view_path.'.builder.text',['name' => 'promo_name','label' => 'Promo Name','value' => (old('promo_name') ? old('promo_name') : $data->promo_name),'attribute' => 'required', 'form_class' => 'col-md-6'])!!}

              {!!view($view_path.'.builder.textarea',['name' => 'description','label' => 'Description','value' => (old('description') ? old('description') : $data->description),'attribute' => 'required', 'form_class' => 'col-md-12'])!!}
            </div>

            <div class="row">   
              <!-- {!!view($view_path.'.builder.file',['name' => 'image','label' => 'Image','value' => '','file_opt' => ['path' => $image_path],'type' => 'file','upload_type' => 'single-image','class' => 'col-md-6','note' => 'Note: File Must jpeg,png,jpg,gif | Best Resolution: 800 x 240 px','form_class' => 'col-md-6'])!!} -->

              {!!view($view_path.'.builder.text',['name' => 'start_date','label' => 'Valid From','value' => (old('start_date') ? old('start_date') : date_format(date_create($data->start_date),'d-m-Y')),'class' => 'datepicker','form_class' => 'col-md-6','attribute' => 'required readonly'])!!}

              {!!view($view_path.'.builder.text',['name' => 'end_date','label' => 'Valid Until','value' => (old('end_date') ? old('end_date') : date_format(date_create($data->end_date),'d-m-Y')),'class' => 'datepicker','form_class' => 'col-md-6','attribute' => 'required readonly'])!!}
            </div>
            <div class="row">
              {!!view($view_path.'.builder.radio',['type' => 'radio','data' => ['y' => 'Active','n' => 'Not Active'],'name' => 'status','label' => 'Status','form_class' => 'col-md-6','value' => (old('status') ? old('status') : $data->status)])!!}

              <!-- {!!view($view_path.'.builder.radio',['type' => 'radio','data' => ['y' => 'Publish','n' => 'Not Publish'],'name' => 'publish','label' => 'Publish','form_class' => 'col-md-6','value' => (old('publish') ? old('publish') : 'n')])!!} -->
            </div>
          </div>
          <div class="tab-pane" id="condition">
            <!-- {!!view($view_path.'.builder.select',['name' => 'promo_type','label' => 'Promo Type','value' => (old('promo_type') ? old('promo_type') : $data->promo_type),'data' => $promo_type,'class' => 'select2 promo_type','attribute' => 'required', 'onchange' => 'checkPromoType()'])!!} -->

            <!-- {!!view($view_path.'.builder.text',['type' => 'number', 'form_class' => 'redeem-point display-none', 'name' => 'redeem_point','label' => 'Redeem Point','value' => (old('redeem_point') ? old('redeem_point') : '')])!!} -->

            <!-- {!!view($view_path.'.builder.radio',['type' => 'radio','data' => ['y' => 'Automatic','n' => 'Manual'],'name' => 'generate_voucher_code','label' => 'Generate Voucher Code','value' => (old('generate_voucher_code') ? old('generate_voucher_code') : 'n'), 'onclick' => 'generateVoucher()', 'form_class' => 'voucher-off display-none'])!!} -->

            {!!view($view_path.'.builder.text',['form_class' => 'voucher-code', 'name' => 'voucher_code','label' => 'Voucher Code','value' => (old('voucher_code') ? old('voucher_code') : $data->voucher_code), 'placeholder' => 'e.g. XBR38239'])!!}

            {!!view($view_path.'.builder.select',['name' => 'discount_type','label' => 'Discount Type','value' => (old('discount_type') ? old('discount_type') : $data->discount_type),'data' => ['p' => 'Percentage','v' => 'Value'],'class' => 'select2','attribute' => 'required'])!!}

            {!!view($view_path.'.builder.text',['type' => 'number','name' => 'discount_value','label' => 'Discount value','value' => (old('discount_value') ? old('discount_value') : $data->discount_value),'attribute' => 'required'])!!}

            <div class="form-group form-md-line-input">
                <div class="form-group" style=''>
                      <label for="tag">Agent</label>
                      <select class="select2" name="agent">
                        <option value="">Select Agent</option>
                        @foreach($user as $u)
                            <option value="{{$u->id}}" {{$data->user_id == $u->id ? 'selected' : ''}}>{{$u->username.' - '.$u->name}}</option>
                        @endforeach
                      </select>
                </div>
            </div>

            <!--{!!view($view_path.'.builder.text',['type' => 'number','name' => 'max_use','label' => 'Maximum use per user','value' => (old('max_use') ? old('max_use') : ''),'attribute' => 'required'])!!}-->
            <div class="form-group">
              
            </div>
            <hr/>
            <!-- <div class="form-group redeem-off">
              <label for="tag">Generate voucher for member?</label>
              <select name="voucher_member_type" class="form-control voucher_member_type">
                <option value="1" {{old('voucher_member_type') ? (old('voucher_member_type') == 1 ? 'selected' : '') : ''}}>All Member</option>
                <option value="2" {{old('voucher_member_type') ? (old('voucher_member_type') == 2 ? 'selected' : '') : ''}}>Random</option>
                <option value="3" {{old('voucher_member_type') ? (old('voucher_member_type') == 3 ? 'selected' : '') : ''}}>Choose Member</option>
              </select>
            </div> -->
           <!--  {!!view($view_path.'.builder.text',['name' => 'voucher_code','label' => 'Voucher Code','value' => (old('voucher_code') ? old('voucher_code') : ''),'form_class' => 'voucher-code','attribute' => 'required'])!!} -->
            <!-- {!!view($view_path.'.builder.text',['name' => 'total_voucher','label' => 'Total Voucher','value' => (old('total_voucher') ? old('total_voucher') : ''),'form_class' => 'auto-on total-voucher redeem-auto'])!!} -->
            
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@push('custom_scripts')
  <script type="text/javascript">
    // function redeem(){
    //   var redeemFlag = $('input[name=redeem_flag]:checked').val();
      
    //   if(redeemFlag == 'y'){
    //     $(".redeem-point").show();
    //     $(".redeem-off").hide();
    //     $(".redeem-auto").hide();
    //     $('.voucher_member_type').val("1");
    //   }
    //   else if(redeemFlag == 'n'){
    //     $(".redeem-point").hide();
    //     $(".redeem-off").show();
    //     $('.voucher_member_type').trigger('change');
    //   }
    // }

    // function generateVoucher(){
    //   var generateVoucher = $('input[name=generate_voucher_code]:checked').val();
    //   var redeemFlag = $('input[name=redeem_flag]:checked').val();

    //   if(generateVoucher == 'y'){
    //     $(".auto-off").hide();

    //     if(redeemFlag == 'y'){
    //       $(".auto-on").show();
    //     }
    //   }
    //   else if(generateVoucher == 'n'){
    //     $(".auto-off").show();

    //     if(redeemFlag == 'y'){
    //       $(".auto-on").hide();
    //     }
    //   }
    // }
    checkPromoType();
    function checkPromoType(){
        var id = $('.promo_type').val();
        console.log(id);
        if(id == 'Voucher'){
          $(".voucher-on").hide();
          $(".voucher-off").show();
        }else{
          $(".voucher-on").show();
          $(".voucher-off").hide();
        }
    }

    $(document).ready(function(){
      
    });
  </script>
@endpush
@endsection
