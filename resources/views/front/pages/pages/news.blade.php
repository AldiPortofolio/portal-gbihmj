@extends($view_path.'.layouts.master')
@section('content')
<div class="row">
  <div class=" swiper-container">
    <div class="swiper-wrapper">
      @foreach($slide as $nw_slide)
        <div class="swiper-slide">
          <img src="{{ asset('components/admin/image/news') }}/{{ $nw_slide->image }}" class="img-responsive img_width" />

          <div class="swiper_cus_con">
            <div class="col-md-offset-6 col-md-6">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 swiper_cus_cons">
                  <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-8 swiper_cus_time">
                      <p>
                        @php
                          $date = date_create($nw_slide->created_at);

                          $day = date_format($date, 'N');

                          $var = [
                                    '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu'
                                 ];

                          $day = $var[$day];
                        @endphp

                        {{ $day }} {{ date_format($date,", d M  Y G:i") }} WIB
                      </p>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 swiper_cus_headline">
                      <h1 class="swiper_cus_headline_font"><b>{{ $nw_slide->title }}</b></h1>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="row swiper_cus_des">
                    {!! substr($nw_slide->description, 0, 150) !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
	  </div>
	    
      <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
    <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>
</div>

<div class="row cus_con nw_con1">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-sm-8 col-xs-12 nw_pad_con1">
        <div class="row nw_bg_con1">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12 nw_con1L nw_con1_pad">
                  <h1>Latest News</h1>
              </div>

              @foreach($content as $news)
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row nw_con1_1L nw_con1_pad">
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="row">
                      <img src="{{ asset('components/admin/image/news') }}/{{ $news->image }}" class="img-responsive img_center" />
                    </div>
                  </div>

                  <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <a href="{{ url('/news') }}/{{ $news->slug }}" class="nw_con1_2L"><h3>{{ $news->title }}</h3></a>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <p class="nw_con1_3L">
                        @php
                          $date = date_create($news->created_at);

                          $day = date_format($date, 'N');

                          $var = [
                                    '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu'
                                 ];

                          $day = $var[$day];
                        @endphp

                        {{ ucfirst($news->username) }} | {{ $day }} {{ date_format($date,", d M  Y G.i") }} WIB
                      </p>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 nw_con1_5L">
                      {!! substr($news->description, 0, 150) !!} <a href="{{ url('/news') }}/{{ $news->slug }}" class="nw_con1_4L">more...</a>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 center">
            <div class="row nw_pg_cus">
              {{ $content->links() }}
            </div>
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
<script>
var swiper = new Swiper('.swiper-container', {
    pagination: '.swiper-pagination',
    paginationClickable: true,
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
});
</script>
@endpush
