<!DOCTYPE html>
<html lang="id">
	<head>
		@include($view_path.'.includes.head')
	</head>
	<body class="style-10">
	   <!--  <div id="loader-wrapper">
	        <div class="bubbles">
	            <div class="title">loading</div>
	            <span></span>
	            <span id="bubble2"></span>
	            <span id="bubble3"></span>
	        </div>
	    </div> -->

	    <span class="loader_image_index" style="width: 100%;height: 100%;position: fixed;top: 50%;text-align: center;z-index: 999;display: none;">
            <img src="{{asset('components/both/images/web/loader.gif')}}" alt="" style="max-width: 38px;">
            <div class="modal-backdrop in"></div>
        </span>
	    
		@include($view_path.'.includes.header')
		<div class="">
			@yield('content')
		</div>
		@include($view_path.'.includes.footer')
	    
    	<div class="clear"></div>
	</body>
</html>