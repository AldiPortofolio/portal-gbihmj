@extends($view_path.'.layouts.master')
@section('content')
<div class="row cus_con nwd_con1">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-sm-8 col-xs-12 nw_pad_con1">
        <div class="row nwd_con1_1L nw_bg_con1">
          <div class="col-md-12 col-ms-12 col-xs-12">
            <p class="nwd_time">
            @php
              $date = date_create($content->created_at);

              $day = date_format($date, 'N');

              $var = [
                        '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu'
                     ];

              $day = $var[$day];
            @endphp

            {{ $day }} {{ date_format($date,", d M  Y G.i") }} WIB
            </p>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12">
            <h1 class="nwd_title">{{ $content->title }}</h1>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12 nwd_time">
            <p>{{ ucfirst($content->username) }} - {{ $web_name }}</p>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12 nwd_img">
            <div class="row">
              <img src="{{ asset('components/admin/image/news') }}/{{ $content->image }}" class="img-responsive img_center" />
            </div>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12 nwd_des">
            {!! $content->description !!}
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12 img_sos_con">
            <a class="fb-share" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ url('/'.Request::segment(1).'/'.$content->slug) }}">
              <img src="{{ asset('components/front/images/other/fb-icon.jpg') }}" class="img-responsive img_center img_sosmed"  id="shareBtn" />
            </a>

            <a class=" twitter-share twitter-share-button" href="https://twitter.com/intent/tweet?url={{ url('/'.Request::segment(1).'/'.$content->slug) }}" target="_blank">
             <img src="{{ asset('components/front/images/other/twitter.jpg') }}" class="img-responsive img_center img_sosmed" />               
            </a>

            <a class="google-share" href="https://plus.google.com/share?url={{ url('/'.Request::segment(1).'/'.$content->slug) }}" >
                <img src="{{ asset('components/front/images/other/google-plus.jpg') }}" class="img-responsive img_center img_sosmed" />
            </a>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12 nw_con1_1R">
          <h3>Most Popular</h3>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 nw_con1_2R"></div>

        @foreach($popular as $q => $pop)
        <div class="col-md-12 col-sm-12 col-xs-12 nw_bg_con1 nw_con1_4R">
          <div class="row row-eq-height">
            <div class="col-md-3 col-sm-3 col-xs-3 nw_con1_3R">
                <div class="nw_con1_3R_1">{{ $q+1 }}</div>
            </div>

            <div class="col-md-9 col-sm-9 col-xs-9 nw_con1_5R">
              <a href="{{ url('/news') }}/{{ $pop->slug }}"><h3>{{ $pop->title }}</h3></a>
            </div>
          </div>             
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom_scripts')
  <script type="text/javascript" src="{{asset('components/front/js/medsos.js')}}"></script>
@endpush