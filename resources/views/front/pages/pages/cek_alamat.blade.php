@extends($view_path.'.layouts.master')

@section('content')
<style>
	@media all and (orientation:portrait) and (max-width: 480px)
	{
		table, thead, tbody, th, td, tr { 
		display: block; 
		}

		thead tr { 
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		
		tr { border-top: 1px solid #ccc; }
		
		td { 
		    border: none !important;
		    border-bottom: 1px solid #eee !important;
		    position: relative;
		    padding-left: 50% !important; 
		}
		
		td:before { 

			position: absolute;
			top: 6px;
			left: 6px;
			width: 45%; 
			padding-right: 10px; 
			white-space: nowrap;
		}

		td:nth-of-type(1):before { content: "Propinsi"; }
		td:nth-of-type(2):before { content: "Kota/Kab."; }
		td:nth-of-type(3):before { content: "Kecamatan"; }
		td:nth-of-type(4):before { content: "Kelurahan"; }
		td:nth-of-type(5):before { content: "Kode Pos"; }		
	}
</style>

<div class="container">
	    <div class="row search-box">
	    	<div class="col-md-12 col-sm-12">
	    	 	<form action="{{url($root_path)}}/cek_alamat" method="GET">
		  		<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
	            	<div class="input-group">
	                	<input type="text" placeholder="Ketik Nama Kota / Kecamatan / Kelurahan / Kodepos.." name="search" class="form-control" required="true">
	                	<span class="input-group-btn">
	                    	<button class="btn btn-cari" type="submit">Cari</button>
	                	</span>
	            	</div>
	        	</form>
           	</div>
	    </div>

	    <div class="row">
	      	<div class="col-md-12 col-sm-12">
	            <div class="portlet light bordered">
	                <div class="portlet-title">
	                    <div class="caption">
	                        <!-- <i class="icon-share font-blue"></i> -->
							<h1>
								<span class="caption-subject font-greens bold uppercase">{{$header}}</span>	
							</h1>
	                    </div>
	                    <div class="actions">
	                    </div>
	                </div>
	                <div class="portlet-body">
	                    <div class="scroller" style="" data-always-visible="1" data-rail-visible="0">
							<div class="row">
								<div class="col-md-12 col-sm-12">
						    		<div class="bilik-iklan-1">
						    			Bilik Iklan 1
						    		</div>
						    	</div>
						    </div>
							<br/>
	                        <div class="">
		                        @if(count($data) > 0)
		                            <table class="table table-bordered table-striped">
		                                <thead>
		                                    <tr>
		                                        <th> Provinsi </th>
		                                        <th> Kota / Kabupaten </th>
		                                        <th> Kecamatan </th>
		                                        <th> Kelurahan </th>
		                                        <th> Kode Pos </th>
		                                    </tr>
		                                </thead>
		                                <tbody>
		                                	@foreach($data as $dt)
		                                		<tr>
		                                			@php
			                                			$province 	= str_replace(" ","-",strtolower($dt->province_name));
			                                			$city 		= str_replace(" ","-",strtolower($dt->city_name));
			                                			$district 	= str_replace(" ","-",strtolower($dt->district_name));
			                                			$subdistrict 	= str_replace(" ","-",strtolower($dt->subdistrict_name));
			                                			$postcode 	= $dt->postcode;
			                                		@endphp
			                                        <td> <a href="{{url('provinsi/'.$province)}}">{{$dt->province_name}}</a> </td>
			                                        <td> <a href="{{url('daerah/'.$province.'/'.$city)}}">{{$dt->city_name}}</a> </td>
			                                        <td> <a href="{{url('daerah/'.$province.'/'.$city.'/'.$district)}}">{{$dt->district_name}}</a> </td>
			                                        <td> <a href="{{url('daerah/'.$province.'/'.$city.'/'.$district.'/'.$subdistrict)}}">{{$dt->subdistrict_name}}</a> </td>
			                                        <td> <a href="{{url('kodepos/'.$postcode)}}">{{$dt->postcode}}</a> </td>
			                                    </tr>
		                                	@endforeach
		                                </tbody>
		                            </table>
	                            	<p style="text-align:right;">{{ $data->appends(request()->except('page'))->links() }}</p>
	                            @else
		                            "Tidak ada hasil pencarian <b>{{$search}}</b>"
		                        @endif
	                        </div>
	                        <br/>
	                        <div class="row">
								<div class="col-md-12 col-sm-12">
						    		<div class="bilik-iklan-2">
						    			Bilik Iklan 2
						    		</div>
						    	</div>
						    </div>
	                    </div>
	                </div>


	            </div>
	        </div>
	    </div>
</div>

<hr/>

<div class="clear"></div>

@push('script')
	
@endpush
@endsection