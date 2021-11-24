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
	            <td><b>Quantity<b></td>
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
                <td> {{$dt->qty}} </td>
            </tr>
	          <tr><td colspan="8"><hr/></td></tr>
	          @endforeach
	        </tbody>
		</table>
		<br/>

		<table width="50%" cellspacing="0" cellpadding="0" align="left">
			<tr>
				<td>Delivery Info<hr/></td>
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
	</body>
</html>