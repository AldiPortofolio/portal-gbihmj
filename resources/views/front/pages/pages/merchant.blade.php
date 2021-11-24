@extends($view_path.'.layouts.master')
@section('content')
<div class="row cus_con mc_con">
  <div class="col-md-3 col-sm-3 col-xs-12">
    <div class="row mc_col_1">
      <div class="col-md-12 col-sm-12 col-xs-12 mc_col1_1">
        <div class="row">
  	  	  <p>SORT BY : </p>
  	  	</div>
  	  </div>

  	  <form action="filter" method="POST">
  	  {{ csrf_field() }}

  	  <div class="col-md-12 col-sm-12 col-xs-12">
  	  	<div class="row">
  	  	  <div class="col-md-10 col-sm 10 col-xs-10 mc_col1_2">
  	  	    <div class="row">
  	  	      <div class="input-group">
			    <input type="text" class="form-control" name="filter" required>
			    
			    <div class="input-group-btn">
				  <button class="btn btn-default mc_sb" type="submit">
				    <i class="glyphicon glyphicon-search"></i>
				  </button>
			    </div>
			  </div>
			</div>
  	  	  </div>
  	  	</div>
  	  </div>

  	  <div class="col-md-12 col-sm-12 col-xs-12 mc_col1_3">
  	  	<div class="row table-responsive">
  	  	  <table width="100%">
  	  	  	<tr>
  	  	  	  <td width="2%">Category</td>
  	  	  	  <td width="5%"><input type="checkbox" name="category" value="cat"></td>
  	  	  	</tr>

  	  	  	<tr>
  	  	  	  <td width="2%">Location</td>
  	  	  	  <td width="5%"><input type="checkbox" name="location" value="loc"></td>
  	  	  	</tr>
  	  	  </table>
  	  	</div>
  	  </div>
  	  </form> 
  	</div>
  </div>

  <div class="col-md-6 col-sm-6 col-xs-12">
    @if(count($merchant) > 0)
    	<div class="row panel-group" id="accordion">
      @php 
        $mcd = "";
      @endphp

      @foreach($merchant as $q => $mc)
        <div class="panel panel-default mc_ac_def">
          <div class="panel-heading mc_cus_tab mc_tab_{{ $q }} {{ $loop->first ? 'mc_tab_active' : 'mc_tab_inactive' }}">
            <h4 class="panel-title">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#ac_{{ $q }}">
                {{ strtoupper($mc->category_name) }}
              </a>
            </h4>
          </div>
          <div id="ac_{{ $q }}" class="mc_cus_ac panel-collapse collapse {{ $loop->first ? 'in' : '' }}" data-id="{{ $q }}">
            <div class="panel-body">
             <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row mc_ac_pad">
                  @foreach($client as $mc_cl)
                    @if($mc_cl->id_merchant_category == $mc->id)
                      <div class="col-md-4 col-sm-4 col-xs-12 mc_ac_col">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <a href="{{ url('/merchants') }}/{{ $mc->id }}">
                            <img src="{{ asset('components/admin/image/merchant') }}/{{ $mc_cl->logo }}" class="img-responsive" />
                          </a>
                        </div>
                      </div>
                    @endif
                  @endforeach
               </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
      </div>
    @else
      <div class="row mc_found">
        <h3>No Result Found.</h3>
      </div>
    @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12">
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
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom_scripts')
<script>
  $(document).ready(function() {
    $(".mc_cus_ac").on("hide.bs.collapse", function(){
      var a = $(this).data('id');
      $(".mc_tab_"+ a).addClass('mc_tab_inactive');
      $(".mc_tab_"+ a).removeClass('mc_tab_active');
    });
    $(".mc_cus_ac").on("show.bs.collapse", function(){
      var a = $(this).data('id');
      $(".mc_tab_"+ a).addClass('mc_tab_active');
      $(".mc_tab_"+ a).removeClass('mc_tab_inactive');

    });
  });
</script>
@endpush