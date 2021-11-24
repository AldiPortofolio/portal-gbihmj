@extends($view_path.'.layouts.master')
@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12 ct_banner" style="background-image: url('{{ asset('components/admin/image/banner')  }}/{{ $content->image  }}')">
  	<div class="row">
  	  <div class="container">
  	    <div class="row">
    	  {!! $content->description !!}
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row cus_con">
  <div class="row">
  	<div class="container">
  	  <div class="row ct_con1 row-eq-height">
    	<div class="col-md-8 col-sm-8 col-xs-12">
 		  <div class="row">
 		  	<div class="col-md-12 col-sm-12 col-xs-12 ct_table table-responsive">
	 		  <table width="60%" class="table">
	 		  	<tr>
	 		  	  <td width="30%">Email </td>
	 		  	  <td width="1%"> : </td>
	 		  	  <td width="40%">{{ $web_email }}</td>
	 		  	</tr>

	 		  	<tr>
	 		  	  <td width="30%">Customer Support</td>
	 		  	  <td width="1%"> : </td>
	 		  	  <td width="40%">{{ $cs_email }}</td>
	 		  	</tr>

	 		  	<tr>
	 		  	  <td width="30%">Call Us</td>
	 		  	  <td width="1%"> : </td>
	 		  	  <td width="40%">{{ $phone }}</td>
	 		  	</tr>
	 		  </table>
	 		</div>

	 		<div class="col-md-12 col-sm-12 col-xs-12 ct_con2">
	 		  <h3>Live Chat</h3>
	 		</div>

	 		<div class="col-md-12 col-sm-12 col-xs-12 ct_con3">
	 		  <a><p>Chat via browser</p></a>
	 		</div>

	 		<div class="col-md-12 col-sm-12 col-xs-12 ct_con4">
	 		  <p>Serving you 24 hours every day, non-stop</p>
	 		</div>
	 	  </div>
    	</div>

    	<div class="col-md-4 col-sm-4 col-xs-12">
    	  <div class="row ct_sos_con">
    	  	<div class="col-md-12 col-sm-12 col-xs-12 ct_sos_1">
    	  	  <h3>SOSIAL MEDIA</h3>
    	  	</div>

    	  	@foreach($medsos as $sos)
    	  	<div class="col-md-12 col-sm-12 col-xs-12">
    	  	  <a href="{{ $sos->value }}"><img src="{{ asset('components/front/images/other') }}/{{ $sos->name.'.jpg' }}" class="img-responsive ct_sos_img" /></a>
    	  	  <a href="{{ $sos->value }}"><p class="ct_sos_2">{{ ucfirst($sos->name) }}</p></a>
    	  	</div>
    	  	@endforeach
    	  </div>
    	</div>
  	  </div>
  	</div>
  </div>
</div>

<div class="row ct_g">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="row">
     <iframe src="{{ $google_map }}" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>

     <div class="ct_gt_con">
      <div class="ct_gt_pad">
     	  <p class="ct_gtl">Operation and Customer Care</p>

        <div class="ct_gda">{!! $address !!}</div>

        <img src="{{ asset('components/back/images/admin') }}/{{ $web_logo }}" class="img-responsive img_center ct_gimg" />
      </div>
     </div>
    </div>
  </div>
</div>
@endsection
