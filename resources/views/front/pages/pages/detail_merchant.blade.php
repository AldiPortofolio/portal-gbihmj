@extends($view_path.'.layouts.master')
@section('content')
<div class="row cus_con mc_con">
  <div class="col-md-8 col-sm-8 col-xs-12">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 dm_tl">
        <h3>{{ strtoupper($client->merchant_name) }}</h3>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
          <img src="{{ asset('components/admin/image/merchant_category') }}/{{ $client->image }}" class="img-responsive img_center" />
        </div>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
          <ul class="nav nav-pills nav-justified dm_tab">
            <li class="active"><a data-toggle="pill" href="#menu_1">CONTACT US</a></li>
            <li><a  data-toggle="pill" href="#menu_2">OUTLET</a></li>
            <li><a  data-toggle="pill" href="#menu_3">PROMO</a></li>
          </ul>
        </div>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12 dm_tab_con">
        <div class="tab-content">
          <div id="menu_1" class="tab-pane dm_tab_pad fade in active">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12 dm_tab_tl1">
                <h3>CONTACT US</h3>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12 dm_tab_content1">
                <div class="row">
                  <div class="col-md-9 col-sm-9 col-xs-12 table-responsive dm_table">
                    <table width="100%">
                      <tr>
                        <td width="5%">Phone</td>
                        <td width="1%"> : </td>
                        <td width="20%">{{ $client->phone }}</td>
                      </tr>

                      <tr>
                        <td width="5%">Address</td>
                        <td width="1%"> : </td>
                        <td width="20%">{{ $client->address }}</td>
                      </tr>

                      <tr>
                        <td width="5%">Email</td>
                        <td width="1%"> : </td>
                        <td width="20%">{{ $client->email }}</td>
                      </tr>
                    </table>
                  </div>

                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <img src="{{ asset('components/admin/image/merchant') }}/{{ $client->logo }}" class="img-responsive img_center" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div id="menu_2" class="tab-pane dm_tab_pad fade">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12 dm_tab_content2">
              @if(count($outlet) > 0)
                <table>
                  <tr>
                    <td><h3>Outlet :</h3></td>
                    <td>
                      @foreach($outlet as $q => $ot)
                        <p><span>{{ $q+1 }}</span> {{ $ot->outlet_name }}</p>
                      @endforeach
                    </td>
                  </tr>
                </table>
              @else
                  <h3>No Outlet Available</h3>
              @endif
              </div>
            </div>
          </div>

          <div id="menu_3" class="tab-pane dm_tab_pad2 fade">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12 dm_tab_content3">
                <h3>Promo Bulan Ini :</h3>
              </div>
              @if(count($promo) > 0)
                @foreach($promo as $prm)
                <div class="col-md-12 col-sm-12 col-xs-12 dm_promo">
                  <a href="{{ url('/promo') }}/{{ $prm->id }}">
                    <img src="{{ asset('components/admin/image/promo') }}/{{ $prm->image }}" class="img-responsive img_center img_width" />
                  </a>
                </div>
                @endforeach
              @else
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <h3>No Promo Available</h3>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-offset-1 col-md-3 col-sm-offset-1 col-sm-3 col-xs-12">
    <div class="row">
      <div class="col-md-offset-2 col-md-10 col-sm-offset-2 col-sm-10 col-xs-12">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 mc_mp_tl">
            <h3>MOST POPULAR</h3>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row mc_mp_logo">
            @foreach($populer as $pop)
              <div class="col-md-6 col-sm-6 col-xs-12">
                <a href="{{ url('/merchants') }}/{{ $pop->id }}">
                  <img src="{{ asset('components/admin/image/merchant') }}/{{ $pop->logo }}" class="img-responsive" />  
                </a>              
              </div>
            @endforeach
            </div>
          </div>
        </div>

        @if(count($related) > 0)
        <div class="row dm_rel">
          <div class="col-md-12 col-sm-12 col-xs-12 mc_mp_tl">
            <h3>YOU MIGHT LIKE</h3>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row mc_mp_logo">
            @foreach($related as $rel)
              <div class="col-md-6 col-sm-6 col-xs-12">
                <a href="{{ url('/merchants') }}/{{ $rel->id }}">
                  <img src="{{ asset('components/admin/image/merchant') }}/{{ $rel->logo }}" class="img-responsive" />  
                </a>              
              </div>
            @endforeach
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

