<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<head>
		<title>Invoices</title>
		<style>
			hr{
				color:gray;
			}
		</style>
	</head>
	<body>
		<table cellspacing="0" cellpadding="0" align="left">
			<tr>
				<td><img src="{{asset('components/both/images/web')}}/{{$web_logo}}" width="200"></td>
			</tr>
		</table>
		<br/><br/><br/><br/>
		<div><hr/></div>
		<table width="70%" cellspacing="0" cellpadding="0" align="left">
			<tr>
				<td>
					<b>Order Number</b>: #{{$order->order_id}} <br/>
					<b>Order Status</b>: {{$order->orderstatus->order_status_name}}
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" align="right">
			<tr>
				<td>Order Date: {{date_format(date_create($order->order_date),'d-m-Y H:i:s')}}</td>
			</tr>
		</table>
		<br/><br/><br/>
		<table width="50%" cellspacing="0" cellpadding="0" align="left">
			<tr>
				<td>From: <hr/></td>
			</tr>
			<tr>
				<td><b>{{$order->orderbilling->first_name}} {{$order->orderbilling->last_name}}</b></td>
			</tr>
			<tr>
				<td>{{$order->orderbilling->phone}}</td>
			</tr>
			<tr>
				<td>{{$order->orderbilling->address}}</td>
			</tr>
			<tr>
				<td>{{$order->orderbilling->province}}, {{$order->orderbilling->city}} - {{$order->orderbilling->sub_district}}</td>
			</tr>
			<tr>
				<td>{{$order->orderbilling->country}}</td>
			</tr>
			<tr>
				<td>{{$order->orderbilling->postal_code}}</td>
			</tr>
		</table>
		<table width="50%" cellspacing="0" cellpadding="0" align="right">
			<tr>
				<td>To: <hr/></td>
			</tr>
			<tr>
				<td><b>{{$order->orderaddress->first_name}} {{$order->orderaddress->last_name}}</b></td>
			</tr>
			<tr>
				<td>{{$order->orderaddress->phone}}</td>
			</tr>
			<tr>
				<td>{{$order->orderaddress->address}}</td>
			</tr>
			<tr>
				<td>{{$order->orderaddress->province}}, {{$order->orderaddress->city}} - {{$order->orderaddress->sub_district}}</td>
			</tr>
			<tr>
				<td>{{$order->orderaddress->country}}</td>
			</tr>
			<tr>
				<td>{{$order->orderaddress->postal_code}}</td>
			</tr>
		</table>

		<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><hr/>
		<table width="100%" cellspacing="0" cellpadding="0">
			<thead>
	          <tr>
	            <td></td>
	            <td><b>Product<b></td>
	            <td><b>Attribute<b></td>
	            <td><b>Unit Price<b></td>
	            <td><b>Quantity<b></td>
	            <td><b>Weight<b></td>
	            <td><b>Total Price<b></td>
	          </tr>
	          <tr>
	            <td colspan="8"><hr/></td>
	          </tr>
	        </thead>
	        <tbody>
	          @foreach ($order->orderdt as $dt)
	          <tr>
                <td>
                    <img src="{{asset($dt->thumbnail)}}" width="80"> 
                </td>
                <td>
                    <b>{{$dt->product_code}}</b><br/>
                    {{$dt->product_name}}
                <td>
                    @foreach((array)json_decode($dt->attribute_description) as $ad => $q)
                        {{$ad}} : {{$q}} <br/>
                    @endforeach
                </td>
                <td> IDR {{number_format($dt->price,0,',','.')}}</td>
                <td> {{$dt->qty}} </td>
                <td> Per Item: {{$dt->weight}} <br/> Total Weight: {{$dt->total_weight}}</td>
                <td> IDR {{number_format($dt->total_price,0,',','.')}} </td>
            </tr>
	          <tr><td colspan="8"><hr/></td></tr>
	          @endforeach
	        </tbody>
		</table>
		<br/>

		<table width="50%" cellspacing="0" cellpadding="0" align="left">
			<tr>
				<td>Delivery Info & Payment<hr/></td>
			</tr>
			<tr>
				<td><b>Payment Methods: </b></td>
				<td>{{$order->payment_method->payment_method_name}}</td>
			</tr>
			<tr>
 	            <td><b>Shipping Vendor: </b></td>
 	            <td>{{$order->orderaddress->delivery_service_name}}</td>
 	        </tr>
			@if($order->tracking_code)
				<tr>
					<td><b>Tracking Code: </b></td>
					<td>{{$order->tracking_code}}</td>
				</tr>
			@endif
		</table>
		<table width="50%" cellspacing="0" cellpadding="0" align="right">
			<tr>
				<td>Summary<hr/></td>
			</tr>
			<tr>
				<td><b>Sub Total: </b></td>
				<td>IDR {{number_format($order->sub_total_amount,0,',','.')}}</td>
			</tr>
			<tr>
				<td>
					<b>Total Shipping Amount: </b><br/>
					<small>Total Weight : {{$order->total_weight}} Kg</small><br/>
                    <small>Shipping Amount : IDR {{number_format($order->shipping_amount,0,',','.')}}</small>
				</td>
				<td>IDR {{number_format($order->total_shipping_amount,0,',','.')}}</td>
			</tr>
            @if($order->voucher_amount > 0)
              	<tr>
	                <td><b>Voucher Amount:</b></td>
	                <td>
	                  (IDR {{number_format($order->voucher_amount,0,',','.')}}) 
	                </td>
              	</tr>
            @endif
			<tr>
				<td><b>Total Amount: </b></td>
				<td>IDR {{number_format($order->total_amount,0,',','.')}}</td>
			</tr>
		</table>
	</body>
</html>