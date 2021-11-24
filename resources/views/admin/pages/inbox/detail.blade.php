@extends($view_path.'.layouts.master')
@section('content')
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
  <div class="portlet-body">
    <div class="row">
      <div class="col-md-3">
          <ul class="list-unstyled profile-nav">
              <li>
                @if($inbox->sender == '1')
                  <img src="{{asset('components/both/image/customer')}}/{{ $inbox->cst_id }}/{{ $inbox->images }}" onerror="this.src='{{asset('components/admin/image/default.jpg') }}';" class="img-responsive pic-bordered" alt="" />
                @else
                  <img src="{{asset('components/both/image/mitra')}}/{{ $inbox->mt_id }}/{{ $inbox->images }}" onerror="this.src='{{asset('components/admin/image/default.jpg') }}';" class="img-responsive pic-bordered" alt="" />
                @endif
              </li>
          </ul>
      </div>

      <div class="col-md-9">
        <div class="row">
          <div class="col-md-2">
            <p class="sbold cus_p">From <span class="t2dot"> : </span></p>
          </div>

          <div class="col-md-4 text-left">
            <p class="cus_p">
              {{ $inbox->name }}
            </p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <p class="sbold cus_p">To <span class="t2dot"> : </span></p>
          </div>

          <div class="col-md-4 text-left">
            <p class="cus_p">
              @php
                $to = json_decode($inbox->to_user_id);
              @endphp

              {{ $to[0] }}
            </p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <p class="sbold cus_p">Tanggal <span class="t2dot"> : </span></p>
          </div>

          <div class="col-md-4 text-left">
            <p class="cus_p">
              @php 
                  $date = date_create($inbox->created_at);
              @endphp

              {{ date_format($date,"d - m - Y") }}
            </p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <p class="sbold cus_p">Subject <span class="t2dot"> : </span></p>
          </div>

          <div class="col-md-4 text-left">
            <p class="cus_p">
              {{ $inbox->subject }}
            </p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <p class="sbold cus_p">Message <span class="t2dot"> : </span></p>
          </div>

          <div class="col-md-4 text-left">
            <p class="cus_p">
              {{ $inbox->message }}
            </p>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom_scripts')
  <script>
    $(document).ready(function(){
      $('input,select,textarea,checkbox,.remove-single-image').prop('readonly',true);
      tinymce.settings = $.extend(tinymce.settings, { readonly: 1 });
    });
  </script>
@endpush
