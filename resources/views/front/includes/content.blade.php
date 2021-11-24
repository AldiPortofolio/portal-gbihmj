@extends('front.mail.master')
@section('content')
<div id="content">
	<div id="content1">
		@if($status == "admin")
			{!! $adm_content !!}
		@elseif($status == "cust")
			{!! $cust_content !!}
		@endif
	</div>
</div>
@endsection