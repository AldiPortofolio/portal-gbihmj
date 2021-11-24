@extends($view_path.'.layouts.master')
@section('content')
<div class="row cus_con oc_con">
  <div class="container">
	<div class="row">
	  <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8 col-xs-12">
	  	<div class="row">
		  <ul class="nav nav-pills nav-justified cus_tab_oc">
		  	@foreach($content as $q => $oc_con)
		   	  <li class="{{ $loop->first ? 'active' : '' }}">
		   	    <a href="#menu_{{ $q }}" data-toggle="pill">
		   	      <img id="oc_menu_{{ $q }}" data-hv="{{ asset('components/admin/image/our_company') }}/{{ $oc_con->image }}" src="{{ asset('components/admin/image/our_company') }}/{{ $loop->first ? $oc_con->image_hover : $oc_con->image }}" class="img-responsive img_center" />

		   	       <p class="oc_tab_p">{{ $oc_con->our_company_name }}</p>
		   	    </a>
		   	  </li>
		  	@endforeach
		  </ul>
		</div>

		<div class="row">
		  <div class="tab-content">
		    @foreach($content as $q => $oc_con)
			  <div id="menu_{{ $q }}" class="tab-pane fade {{ $loop->first ? 'in active' : '' }} oc_tab_des">
				{!! $oc_con->description !!}
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