@extends($view_path.'.layouts.master')
@section('content')
<div class="row cus_con">
  <div class="col-md-12 col-sm-12 col-xs-12">
  	<div class="row">
  	  <img src="{{ asset('components/admin/image/promo') }}/{{ $promo->image }}" class="img-responsive img_center img_width" />
  	</div>
  </div>

  <div class="col-md-12 col-sm-12 col-xs-12 prm_cons">
  	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12 prm_con">
	  	<div class="row">
	  		<h1>{{ $promo->promo_name }}</h1>
	  	</div>
	  </div>

	  <div class="col-md-12 col-sm-12 col-xs-12 prm_con2">
	  	<div class="row">
	  	  <h3>Deskripsi :</h3>
	  	  <div class="prm_con3">{!! $promo->description !!}</div>
	  	</div>
	  </div>

	  <div class="col-md-12 col-sm-12 col-xs-12 prm_con4">
	  	<div class="row">
	  	  <h3>Term & Conditions :</h3>
	  	  <div class="prm_con5">{!! $promo->term_condition !!}</div>
	  	</div>
	  </div>
	</div>
  </div>
</div>
@endsection