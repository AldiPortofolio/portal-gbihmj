<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="format-detection" content="telephone=no" />
		<meta http-equiv="Content-Language" content="id">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, minimal-ui"/>
		<title>Jukir Online | Forgot Password</title>

		<meta content="" name="keywords" />
		<meta content="" name="description" />
		<meta content="Vincent" name="author" />
		<meta name="geo.placename" content="Indonesia">
		<meta name="geo.country" content="ID">
		<meta name="language" content="Indonesian">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<meta name="root_url" content="{{url($root_path)}}/" />

		<!-- <link href='http://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700%7CDancing+Script%7CMontserrat:400,700%7CMerriweather:400,300italic%7CLato:400,700,900' rel='stylesheet' type='text/css' /> -->

		<!-- Core CSS -->
		<link rel ="stylesheet" href="{{asset('components/plugins/bootstrap/css/bootstrap.min.css')}}">
		<link rel ="stylesheet" href="{{asset('components/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
		<link rel ="stylesheet" href="{{asset('components/front/css/idangerous.swiper.css')}}">
		<link rel ="stylesheet" href="{{asset('components/front/css/style.css')}}">
	</head>
	<body>
		<div class="vertical-center">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="panel panel-default">
							<div class="panel-heading">Reset Password</div>
							<div class="panel-body">
								

								<form class="form-horizontal" role="form" method="POST" action="{{url($view_path.'/account/reset')}}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" name="token" value="{{ $token }}">

									<div class="form-group">
										<label class="col-md-4 control-label">Password</label>
										<div class="col-md-6">
											<input type="password" class="form-control" name="password">
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label">Confirm Password</label>
										<div class="col-md-6">
											<input type="password" class="form-control" name="password_confirmation">
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Reset Password
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>