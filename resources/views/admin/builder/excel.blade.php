<html>
	<head>
		<style>
			th{
				background-color: #9a6b37;
				color:#fff;
			}
			td{
				border: 1px solid #000000;
			}
		</style>
	</head>
	<body>
		<table border="1">
			<thead>
				<tr style="backgroound:pink;">
					@foreach($data['header'] as $h)
						<th align="center">{{$h}}</th>
					@endforeach
				</tr>
				@foreach($data['data'] as $d)
					<tr>
						@foreach($data['header'] as $h)
							<td>{{$d->$h}}</td>
						@endforeach
					</tr>
				@endforeach
			</thead>
		</table>
	</body>
</html>