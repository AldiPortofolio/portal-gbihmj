<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    	<meta content="text/html;charset=utf-8" http-equiv="content-type">

		<style>
			.logo{
				max-height:80px;
			}

			.con_body{
				max-width:600px;
				border:1px solid #603813;
				display:block;
				margin:auto;
			}

			#head{
				width:100%;
				background-color:#603813;
				height:auto;
				color:#ffffff;
			}

			#head_L{
				width: 68%;
				display:inline-block;
				vertical-align:middle;
				padding:20px;
			}

			#head_L h1{
				margin-top:0;
				margin-bottom:0;
			}

			#head_R{
				width:18%;
				display:inline-block;
				vertical-align:middle;
				padding:20px;
			}

			#head_R img{
				display:block;
				margin:auto 0 auto auto;
			}

			#content{
				width:100%;
				word-break:break-all;
			}

			#content h3{
				color:#603813;
				margin-top:40px;
			}

			#content1{
				margin:40px;
			}

			#content2{
				margin:40px;
			}

			#content2_1{
				color:#603813;
				margin-bottom:0 !important;
			}

			#content2_2{
				margin-left:20px;
				margin-top:8px !important;
			}

			#content3{
			}

			#content4{
			}

			#content4_1{
				margin-bottom:0;
			}

			#content4_2{
				margin-top:8px;
			}

			#content5{
			}

			.content_5_1 p{
				margin-bottom:10px;
			}

			#content_5_2_L{
				padding-right:10px;
			}

			#content_5_2_R{
				padding-left:10px;
			}			

			.content5_2 p{
				margin-top:0;
				margin-bottom:8px;
			}

			.color1{
				color:#603813;
			}

			.padding1{
				padding:20px;
			}

			#cus_table{
				border-spacing: 0;
			}

			.cus_table1{
				border:1px solid #603813;
			}

			#cus_table2{
				border-spacing: 0;
			}
		</style>
	</head>
	<body>
		<div class="con_body">
		   	@include('api.mail.header')
		    @yield('content')
		</div>
	</body>
</html>
