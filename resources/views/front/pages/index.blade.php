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
			border: none;
			border-bottom: 1px solid #eee; 
			position: relative;
			padding-left: 50%; 
		}
		
		td:before { 

			position: absolute;
			top: 6px;
			left: 6px;
			width: 45%; 
			padding-right: 10px; 
			white-space: nowrap;
		}

		/*td:nth-of-type(1):before { content: "Propinsi"; }
		td:nth-of-type(2):before { content: "Kota/Kab.2"; }
		td:nth-of-type(3):before { content: "Kecamatan"; }
		td:nth-of-type(4):before { content: "Kelurahan"; }
		td:nth-of-type(5):before { content: "Kode Pos"; }*/		
	}


	.table>tbody>tr>td{
		border-bottom: 1px solid #e7ecf1;
	}

	.row_nomargin {
	    margin-left: 0;
	    margin-right: 0;
	}

	/*Fast order menu*/
    .txt_input{
        color: white;
        font-size: 18px;
    }

    .form-group input{
    	width: 100% !important;
    }

    span.select2{
    	width: 100% !important;
    }
    /*End fast order menu*/
</style>

<section id="main-slider" class="no-margin">
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
		<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
		<li data-target="#myCarousel" data-slide-to="1"></li>
		<li data-target="#myCarousel" data-slide-to="2"></li>
		</ol>

		<!-- Wrapper for slides -->
		<div class="carousel-inner">
		@foreach($slide as $key => $s)
            <div class="item {{ $key == 0 ? 'active' : ' ' }}">
              <img src="{{ asset('components/both/images/slideshow/'.$s->image) }}" class="img-responsive hm_con3_img" />
            </div>
        @endforeach
		<!-- 
		<div class="item">
		  <img src="chicago.jpg" alt="Chicago">
		</div>

		<div class="item">
		  <img src="ny.jpg" alt="New York">
		</div> -->
	</div>

	  <!-- Left and right controls -->
	  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#myCarousel" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right"></span>
	    <span class="sr-only">Next</span>
	  </a>
	</div>
    <!--/.carousel-->
</section>
<!--/#main-slider-->


<!-- menu fast order -->
<form class="form-inline" role="form" action="{{url($root_path)}}/booking" method="post" id="form_lang" enctype="multipart/form-data">
        {{ csrf_field() }}
    <div class="row row_nomargin menu_order">
        <div class="form-group col-md-3 col-xs-6" style="">
            <label class="txt_input" for="exampleInputEmail22" style="">{{trans('general.kemana-tujuan-anda')}}</label>

			<select class="select2 multiple country" id="country" name="country[]" multiple="">
			@foreach($country as $u)
			    <option value="{{$u->continent_name.'-'.$u->id}}">{{$u->country_name}}</option>
			@endforeach
			</select>
        </div>
        <div class="form-group col-md-2 col-xs-6" style="">
            <label class="txt_input" for="exampleInputPassword42">{{trans('general.dari-tanggal')}}</label>
            <div class="input-icon">
                <i class="fa fa-calendar"></i>
                <!-- <input type="password" class="form-control" id="exampleInputPassword42" placeholder="Password"> -->
                <input type="text" id="date1" class="form-control date1" name="date_from" readonly="" placeholder="Date From" value="">
            </div>
        </div>
        <div class="form-group col-md-2 col-xs-6" style="">
            <label class="txt_input" for="exampleInputPassword42">{{trans('general.sampai-tanggal')}}</label>
            <div class="input-icon">
                <i class="fa fa-calendar"></i>
                <!-- <input type="password" class="form-control" id="exampleInputPassword42" placeholder="Password"> -->
                <input type="text" id="date2" class="form-control date2" name="date_to" readonly="" placeholder="Date To" value="">
            </div>
        </div>
        <div class="form-group col-md-1 col-xs-6" style="">
            <label class="txt_input" for="exampleInputPassword42">{{trans('general.kuantiti')}}</label>
            <div class="">
                <!-- <i class="fa fa-calendar"></i> -->
                <input type="number" class="form-control qty" id="exampleInputPassword42" name="qty" value="1">
            </div>
        </div>
        <div class="form-group col-md-2 col-xs-6" style="">
            <label class="txt_input" for="exampleInputPassword42">{{trans('general.biaya-sewa')}}</label>
            <div class="">
                <!-- <i class="fa fa-calendar"></i> -->
                <input type="text" class="form-control rental_fee" id="" name="rental_fee" placeholder="IDR 0" readonly="">
            </div>
        </div>

        <div class="form-group col-md-2 col-xs-6" style="">
        	<div class="checkbox">
	            <label>
	                <!-- <div class="checker"><span><input type="checkbox"></span></div> Remember me </label> -->
	        </div>
        	<!-- <label class="txt_input" for="exampleInputPassword42">&nbsp;&nbsp;&nbsp;</label> -->
        	<button type="submit" class="btn btn-default" style="margin-top: 5px;display: block;">{{trans('general.pesan-sekarang')}}</button>
        </div>
    </div>
</form>
<!-- <div class="container">
    <div class="row">
        <div class='col-sm-6'>
            <input type='text' class="form-control" id='datetimepicker4' readonly="" />
        </div>
      
    </div>
</div> -->

<!-- end menu fast  order -->

<div class="container" style="width: 100%">

  <div class="feature">
    <div class="container" style="width: 100%">
      <div class="text-center">
      	@foreach($feature as $f)
	        <div class="col-md-3" style="margin-top: 40px;">
	          <a class="hi-icon-wrap hi-icon-effect wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
	            <!-- <i class="fa fa-tags"></i> -->
	            <img src="{{ asset('components/both/images/feature/'.$f->image) }}" class="img-responsive img_feature" style="margin:auto;max-width: 70%;">
	            <h2 style="font-size: 1.3em !important;color: #0c104a;font-weight: bold;">{{$f->section_name}}</h2>
	            <p>{{$f->section_description}}</p>
	          </a>
	        </div>
	    @endforeach
       <!--  <div class="col-md-3">
          <div class="hi-icon-wrap hi-icon-effect wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
            <i class="fa fa-laptop"></i>
            <h2>Retina Ready</h2>
            <p>Quisque eu ante at tortor imperdiet gravida nec sed turpis phasellus.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="hi-icon-wrap hi-icon-effect wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="900ms">
            <i class="fa fa-heart-o"></i>
            <h2>Full Responsive</h2>
            <p>Quisque eu ante at tortor imperdiet gravida nec sed turpis phasellus.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="hi-icon-wrap hi-icon-effect wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="1200ms">
            <i class="fa fa-cloud"></i>
            <h2>Friendly Code</h2>
            <p>Quisque eu ante at tortor imperdiet gravida nec sed turpis phasellus.</p>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>

<hr/>

<div class="clear"></div>

@push('custom_scripts')
	<script type="text/javascript">
	$(document).ready(function(){

		// var adbooth_1 = $('#adbooth_1').val();
		// var Tawk_API = adbooth_1;
		// console.log(Tawk_API);
		// var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
		// (function(){
		// var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
		// s1.async=true;
		// s1.src='https://embed.tawk.to/59a53f7b4fe3a1168eada392/default';
		// s1.charset='UTF-8';
		// s1.setAttribute('crossorigin','*');
		// s0.parentNode.insertBefore(s1,s0);
		// })();
		// alert('test');
        // $(function () {
            // $('#datetimepicker4').datepicker();
        // });

		// function showRSS(str) {
		// 	if (str.length==0) { 
		// 	document.getElementById("rssOutput").innerHTML="";
		// 	return;
		// 	}
		// 	if (window.XMLHttpRequest) {
		// 	// code for IE7+, Firefox, Chrome, Opera, Safari
		// 	xmlhttp=new XMLHttpRequest();
		// 	} else {  // code for IE6, IE5
		// 	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		// 	}
		// 	xmlhttp.onreadystatechange=function() {
		// 	if (this.readyState==4 && this.status==200) {
		// 	  document.getElementById("rssOutput").innerHTML=this.responseText;
		// 	}
		// 	}
		// 	xmlhttp.open("GET","getrss?q="+str,true);
		// 	xmlhttp.send();
		// }

		// console.log('test');

		$( ".country" ).select2({
			 theme: "bootstrap"
		});

		$('.country, .qty').on('change', function(){
      		getRentalFee();
      	});

		var date = new Date();
		date.setDate(date.getDate()+4);
		console.log(date);
		$("#date1").datepicker({
	    	changeMonth: true,
	    	changeYear: true,
	    	dateFormat: 'dd-mm-yy',
	    	yearRange: "0:+90",
	    	showButtonPanel: true,
	    	minDate: date,

          	onSelect: function(dateText, inst) {
          		getRentalFee();
          	}

      	});

      	$("#date2").datepicker({
	    	changeMonth: true,
	    	changeYear: true,
	    	dateFormat: 'dd-mm-yy',
	    	yearRange: "0:+90",
	    	showButtonPanel: true,
	    	minDate: date,

          	onSelect: function(dateText, inst) {
          		getRentalFee();
          	}

      	});

      	

      	function getRentalFee(){
	      	var searchCountry   = $("#country").select2('data');
	    	console.log(searchCountry);
	    	var arr 			= [];
	    	var date_from 		= $("#date1").val();
	    	var date_to 		= $("#date2").val();
	    	var qty 			= $(".qty").val();

	    	var returnval = 1;
	    	if(searchCountry.length == 0){
	    		returnval = 0;
	    		// alert('Please select country');
	    	}else{
	    		for(var i in searchCountry){
	              	var getOption 	= searchCountry[i].id;
	    			// var ac 			= getOption.split('-');
					// var country 	= ac[1];
					// var continent 	= ac[0];
	              	// var option = getOption[0];

	              	arr.push(getOption);
	        	}

	    	}
	    	console.log(arr);
	    	console.log(date_from);
	    	console.log(date_to);
	    	if(date_from == "" || date_to == ""){
	    		returnval = 0;
	    		// alert('Please input date from');
	    	}
    		
	    	if(returnval == 1){
		       	var a 				= "./booking/getModemAvailableByCountry";
		    	var files   		= {arr_country: arr, date_from: date_from, date_to: date_to, qty: qty};
		    	console.log(files);
		    	$.postdata(a, files).done(function(data){
		    		console.log(data);
		    		var res = JSON.parse(data);
		    		// var arr = [];
		    		// for(var i=0; i<res.length; i++){
		    		// 	if(res[i]['show'] == 1){
			    	// 		var temp = {
			    	// 			id: res[i]['id'],
						  //       text: res[i]['modem_name']+' - '+res[i]['serial_no']
			    	// 		};
			    	// 		arr.push(temp);
			    	// 	}
		    		// }
			  		//     	var data = [
					//     {
					//         id: 0,
					//         text: 'enhancement'
					//     },
					//     {
					//         id: 1,
					//         text: 'bug'
					//     },
					//     {
					//         id: 2,
					//         text: 'duplicate'
					//     },
					//     {
					//         id: 3,
					//         text: 'invalid'
					//     },
					//     {
					//         id: 4,
					//         text: 'wontfix'
					//     }
					// ];
					// $(".modem").empty();

					// $(".modem").select2({
					//   data: arr,
					//   multiple: true
					// });

					var rental_fee = 0;
					if(res['message'] == 'Stock tersedia'){
						console.log(res['rental_fee']);
						rental_fee = $.formatRupiah(res['rental_fee']);
						console.log(rental_fee);
					}

					$('.rental_fee').val(rental_fee);
			    });
			}
		}
    });
	</script>
@endpush
@endsection