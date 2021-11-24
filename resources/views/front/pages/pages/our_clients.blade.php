@extends($view_path.'.layouts.master')
@section('content')
  <style>
    .swiper-container {
        width: 100%;
        height: 400px !important;
        margin: 20px auto;
    }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        width: 60%;
        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }

    #grad {
	    background: red; /* For browsers that do not support gradients */
		background: -webkit-radial-gradient(yellow, #FF5722, #FF5722); /* Safari 5.1 to 6.0 */
		background: -o-radial-gradient(yellow, #FF5722, #FF5722); /* For Opera 11.6 to 12.0 */
		background: -moz-radial-gradient(yellow, #FF5722, #FF5722); /* For Firefox 3.6 to 15 */
		background: radial-gradient(yellow, #FF5722, #FF5722); /* Standard syntax */
	}

	.swiper-slide-prev{
		width: 20%;
	}
  </style>
	<div class="content-push">
	    <!-- Slider main container -->
		<div class="swiper-container" id="grad">
		    <!-- Additional required wrapper -->
		    <div class="swiper-wrapper">
		        <!-- Slides -->
		        <input type="hidden" id="src-img" value="{{asset('components/admin/image/client_card/')}}">
		        <input type="hidden" id="client_card" value="{{$client_card}}">
		        @foreach($client as $c)
		     		<div class="swiper-slide" id="slide_{{$c->id}}" style="background: none;cursor: -webkit-grab;"><img src="{{asset('components/admin/image/client/'.$c['logo'])}}" width="100%"/></div>
	            @endforeach
		    </div>
		    <!-- If we need pagination -->
		    <!-- <div class="swiper-pagination swiper-pagination-index" style="bottom: 40px !important; text-align: center;left: 0px !important;"></div> -->
		    
		    <!-- If we need navigation buttons -->
		    <div class="arrow-swipper-prev swiper-button-prev" style="left: 30% !important;"></div>
		    <div class="arrow-swipper-next swiper-button-next" style="right: 30% !important;"></div>
		    
		    <!-- If we need scrollbar -->
		    <div class="swiper-scrollbar"></div>
		</div>
	</div>
	<div class="card-header cus_con cus-padding">
		<div class="row">
				<div class="col-sm-5">
					<div class="card-image">
						<img class="img-card-header" src="" width="" style="float:right;"/>
					</div>
				</div>
				<div class="col-sm-7 card-header-right">
					<div class="card-title"></div>
					<div class="card-desc"></div>
				</div>
		</div>
	</div>
	<div class=" card-collection cus_con cus-padding">
		<div class="row">
			<div class="col-sm-4 card-collections" id="card-collection_1">
				<div style="height: 135px;">
					<img class="card-img" id="card-img_1" src="" width="100%" height="100%"/>
					<div class="card-title2" id="card-title2_1"></div>
				</div>
			</div>
			<div class="col-sm-4 card-collections" id="card-collection_2">
				<div style="height: 135px;">
					<img class="card-img" id="card-img_2" src="" width="100%" height="100%"/>
					<div class="card-title2" id="card-title2_2"></div>
				</div>
			</div>
			<div class="col-sm-4 card-collections" id="card-collection_3">
				<div style="height: 135px;">
					<img class="card-img" id="card-img_3" src="" width="100%" height="100%"/>
					<div class="card-title2" id="card-title2_3"></div>
				</div>
			</div>
		</div>
		<div class="row cus-padding">
			<div class="col-sm-12 text-align-center">
				<button class="button btn-apply-now" data-id=""><a href="{{url('/apply')}}" style="color: #fff;">APPLY NOW</button>
			</div>
		</div>
	</div>
<hr/>
<div class="clear"></div>
@endsection

@push('custom_scripts')
<script>
	$(document).ready(function(){
		var swiper = new Swiper('.swiper-container', {
		        pagination: '.swiper-pagination',
		        slidesPerView: 3,
		        centeredSlides: true,
		        paginationClickable: true,
		        spaceBetween: 30,
		        grabCursor: true,
		        nextButton: '.swiper-button-next',
			    prevButton: '.swiper-button-prev',
			    onSlideChangeStart: function(){
			    	changeContent();
			    },
		    });

		function changeContent(){

			var slide_active = $('.swiper-slide-active').attr('id');

			var id = slide_active.split('_');
			id = id[1];
			console.log('id: '+id);
			var src_img = $('#src-img').val();
			var client_card = JSON.parse($('#client_card').val());

			$(".img-card-header").attr("src", '');
			$(".card-title").text('');
			$(".card-desc").text('');
			for(var i=0; i<client_card.length; i++){
				if(client_card[i]['id_client'] == id){
					$(".img-card-header").attr("src", src_img + '/' +client_card[i]['image']);
					$(".card-title").text(client_card[i]['client_card_name']);
					$(".card-desc").text(client_card[i]['description']);
					break;
				}
			}
			console.log(client_card);	
			var k = 1;
			$('.card-img').attr('src', '');
			$('.card-title2').text('');
			for(var j=0; j<client_card.length; j++){
				if(k <= 3){
					if(client_card[j]['id_client'] == id){
						console.log('#card-img_'+k);
						$('#card-img_'+k).attr("src", src_img + '/' +client_card[j]['image']);
						$('#card-title2_'+k).text(client_card[j]['client_card_name']);
						k++;
					}
				}
					
			}
		}

		changeContent();
	});

</script>
@endpush
