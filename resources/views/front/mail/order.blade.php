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

	<div id="content2">
		<h3>Our Bank Details</h3>
		<div id="content2_1"><b>{{ $orderhd->bank_info }}</b></p>
	</div>

	<div id="content3">
		<h3 class="color1">ORDER #{{ $orderhd->order_id }}</h3>

		<table width="100%" id="cus_table">
			<tr>
				<td class="color1 padding1 cus_table1"><b>Product</b></td>
				<td class="color1 padding1 cus_table1"><b>Quantity</b></td>
				<td class="color1 padding1 cus_table1"><b>Price</b></td>
			</tr>
			@foreach($orderdt as $dt)
			<tr>
				<td class="padding1 cus_table1">{{ $dt->product_name }}</td>
				<td class="padding1 cus_table1">{{ $dt->qty }}</td>
				<td class="padding1 cus_table1">Rp {{ $dt->total_price }} Via {{ $orderaddress->delivery_service_name }}</td>
			</tr>
			@endforeach
			<tr>
				<td colspan="2" class="color1 padding1 cus_table1"><b>Subtotal:</b></td>
				<td class="padding1 cus_table1">Rp {{number_format($orderhd->sub_total_amount),0,',','.'}}</td>
			</tr>
			<tr>
				<td colspan="2" class="color1 padding1 cus_table1"><b>Voucher:</b></td>
				<td class="padding1 cus_table1">Rp {{number_format($orderhd->voucher_amount),0,',','.'}}</td>
			</tr>
			<tr>
				<td colspan="2" class="color1 padding1 cus_table1"><b>Shipping:</b></td>
				<td class="padding1 cus_table1">Rp {{number_format($orderhd->shipping_amount),0,',','.'}} via {{ $orderaddress->delivery_service_name }}</td>
			</tr>
			<tr>
				<td colspan="2" class="color1 padding1 cus_table1"><b>Payment Method:</b></td>
				<td class="padding1 cus_table1">Direct Bank Transfer</td>
			</tr>
			<tr>
				<td colspan="2" class="color1 padding1 cus_table1"><b>Total:</b></td>
				<td class="padding1 cus_table1">Rp {{number_format($orderhd->total_amount),0,',','.'}}</td>
			</tr>
		</table>
	</div>

	<div id="content4">
		<h3 class="color1">Your Details</h3>
		<p id="content4_1">Email: {{ $mail }}</p>
		<p id="content4_2">Tel: {{ $orderaddress->phone }}</p>
	</div>

	<div id="content5">
		<table width="100%"  id="cus_table2">
			<tr>
				<td class="color1 content5_1"><p><b>Billing Address</b></p></td>
				<td class="color1 content5_1"><p><b>Shipping Address</b></p></td>
			</tr>
			<tr>
				<td class="content5_2" id="content5_2_L">
					<p>{{ $orderaddress->first_name }}</p>
					<p>{{ $orderaddress->address }}</p>
					<p>{{ $orderaddress->city }}</p>
					<p>{{ $orderaddress->province }}</p>
					<p>{{ $orderaddress->postal_code }}</p>
				</td>
				<td class="content5_2" id="content5_2_R">
					<p>{{ $orderaddress->first_name }}</p>
					<p>{{ $orderaddress->address }}</p>
					<p>{{ $orderaddress->city }}</p>
					<p>{{ $orderaddress->province }}</p>
					<p>{{ $orderaddress->postal_code }}</p>
				</td>
			</tr>
		</table>
	</div>
</div>
@endsection