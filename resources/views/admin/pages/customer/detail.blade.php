@extends($view_path.'.layouts.master')
@section('content')
<style>

</style>

  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption font-green">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
      </div>
      <div class="actions">
        <a href="{{url($path)}}/{{$data1->cst_id}}"><button type="button" class="btn red-mint">{{trans('general.back')}}</button></a>
      </div>
    </div>
    <div class="portlet-body form">
      <div class="row">        
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-4">
              <img src="{{ asset('components/both/image/customer') }}/{{ $data1->images }}" onerror="this.src='{{ asset('components/both/image/not_found/customer-none.png') }}';" class="img-responsive" />
            </div>

            <div class="col-md-8">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="sbold">{{ $data1->title }}</h3>
                  <p>{{ $data1->description }}</p>
                </div>

                <div class="col-md-12">
                  <h3 class="sbold">Information</h3>
                </div>

                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      <p class="sbold cus_p">Category <span class="t2dot"> : </span></p>
                    </div>

                    <div class="col-md-8 text-left">
                      <p class="cus_p">
                        {{ $data1->category_name }}
                      </p>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      <p class="sbold cus_p">Tag <span class="t2dot"> : </span></p>
                    </div>

                    <div class="col-md-8 text-left">
                      @php
                        $tag = json_decode($data1->tag_name);
                      @endphp

                      @if(count($tag) > 0)
                      <ol class="cus_ol">
                          @foreach($tag as $q => $tg)
                            <li>{{ $q+1 }}. {{ $tg }}</li>
                          @endforeach
                      </ol>
                      @else
                      -
                      @endif
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      <p class="sbold cus_p">Price <span class="t2dot"> : </span></p>
                    </div>

                    <div class="col-md-8 text-left">
                      <p class="cus_p">
                        Rp. {{ number_format($data1->estimate_price) }}
                      </p>
                    </div>
                  </div>
                </div><br>

                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      <p class="sbold cus_p">Name <span class="t2dot"> : </span></p>
                    </div>

                    <div class="col-md-8 text-left">
                      <p class="cus_p">
                        {{ $data1->cst_name }}
                      </p>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      <p class="sbold cus_p">City <span class="t2dot"> : </span></p>
                    </div>

                    <div class="col-md-8 text-left">
                      <p class="cus_p">
                        {{ $data1->city }}
                      </p>
                    </div>
                  </div>
                </div> 

                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      <p class="sbold cus_p">Address <span class="t2dot"> : </span></p>
                    </div>

                    <div class="col-md-8 text-left">
                      <p class="cus_p">
                        {{ $data1->address }}
                      </p>
                    </div>
                  </div>
                </div> 

                <div class="col-md-12">
                  <div class="row">
                      <div class="col-md-3">
                    <p class="sbold cus_p">Province <span class="t2dot"> : </span></p>
                  </div>

                  <div class="col-md-8 text-left">
                    <p class="cus_p">
                      {{ $data1->prov_name }}
                    </p>
                  </div>
                  </div>
                </div> 

                <div class="col-md-12">
                  <div class="row">
                      <div class="col-md-3">
                    <p class="sbold cus_p">City <span class="t2dot"> : </span></p>
                  </div>

                  <div class="col-md-8 text-left">
                    <p class="cus_p">
                      {{ $data1->city }}
                    </p>
                  </div>
                  </div>
                </div> 

                <div class="col-md-12">
                  <br><h3 class="sbold">Event</h3>
                </div>

                <div class="col-md-12">
                  <div class="row">
                      <div class="col-md-3">
                    <p class="sbold cus_p">Nama Event <span class="t2dot"> : </span></p>
                  </div>

                  <div class="col-md-8 text-left">
                    <p class="cus_p">
                      {{ $data1->event }}
                    </p>
                  </div>
                  </div>
                </div> 

                <div class="col-md-12">
                  <div class="row">
                      <div class="col-md-3">
                    <p class="sbold cus_p">Tanggal Event <span class="t2dot"> : </span></p>
                  </div>

                  <div class="col-md-8 text-left">
                    <p class="cus_p">
                      @php 
                          $date = date_create($data1->event_date);
                      @endphp

                      {{ date_format($date,"d - m - Y") }}
                    </p>
                  </div>
                  </div>
                </div> 

                <div class="col-md-12">
                  <br><h3 class="sbold">Mitra Bid</h3>
                </div>

                <div class="col-md-10">
                  <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                        <tr>
                            <th><b>No</b></th>
                            <th><b>Nama Mitra</b></th>
                            <th><b>Bid Price</b></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data2 as $q => $dt2)
                        <tr>
                          <td>{{ $q+1 }}</td>
                          <td>{{ $dt2["name"] }}</td> 
                          <td>Rp. {{ number_format($dt2["price"]) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div><!-- end row -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
