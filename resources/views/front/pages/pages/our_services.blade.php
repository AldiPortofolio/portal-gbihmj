@extends($view_path.'.layouts.master')
@section('content')
<div class="row cus_con os_con">
  <div class="container">
	<div class="row">
	  <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8 col-xs-12">
	  	<div class="row">
		  <ul class="nav nav-tabs nav-justified">
		  @foreach($content as $q => $os_con)
		    <li class="{{ $loop->first ? 'active' : '' }} os_cus_tab">
		      <a data-toggle="tab" href="#menu_{{ $q }}">
		      	<img src="{{ asset('components/admin/image/our_service') }}/{{ $os_con->icon }}" class="img_center img-responsive" />
		      	<p class="os_tab_p">{{ $os_con->our_service_name }}</p>
		      </a>
		    </li>
		  @endforeach
		  </ul>
		</div>
	  </div>
	</div>

	<div class="row os_tab_content">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<div class="tab-content">
	  	  @foreach($content as $q => $os_con)
		  <div id="menu_{{ $q }}" class="tab-pane fade {{ $loop->first ? 'in active' : '' }}">
		  	<div class="row">
		  		{!! $os_con->description !!}
		  	</div>
		  </div>
		  @endforeach
		</div>
	  </div>
	</div>
  </div>
</div>
@endsection