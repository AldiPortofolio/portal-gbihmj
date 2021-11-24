@extends($view_path.'.layouts.master')
@section('content')
<style type="text/css">
  .create_order{
    margin-top: 20px;
  }
  .page-header{
    display: none;
  }
  .footer-wrapper{
    display: none;
  }
  .row{
    margin: 0;
  }
</style>
  <div class="container" style="">

    <form class="" role="form" action="{{url($root_path)}}/booking/store" method="post" id="form_lang" enctype="multipart/form-data">
      {{ csrf_field() }}
      {{var_dump($data)}}
      <div class="row">
        <div class="col-md-12 header_wrapper">
          <div class="title">Booking anda</div>
          <img class="img_logo_1">
          <img class="img_logo_1">
        </div>
        <div class="col-md-12 event_wrapper">
          <div class="event_title">{{$data->title}}</div>
          <div class="event_subtitle">{{$data->sub_title}}</div>
        </div>
        <div class="col-md-12 event_wrapper-2">
          <div class="col-md-8">
            <div class="event_datetime"><span class="event_date">{{$data->date}}</span>&nbsp;<span class="event_time">{{$data->booking_time}}</span></div>
            <div class="event_church">{{$data->church_name}}</div>
            <div class="event_speaker">{{$data->speaker}}</div>
          </div>
          <div class="col-md-4"></div>
        </div>
        <div class="col-md-12 peserta_wrapper">
        <table>
          <th>
            <tr>Nama Peserta</tr>
            <tr>Nomor Kursi</tr>
          </th>
          <tbody>
            <tr><td>Xavier</td><td>A1</td></tr>
          </tbody>
        </table>
        </div>
      </div>
    </form>
  </div>

<hr/>

<div class="clear"></div>
@endsection

@push('custom_scripts')
<script type="">
  $(document).ready(function() {
   
    }
  });
</script>
@endpush