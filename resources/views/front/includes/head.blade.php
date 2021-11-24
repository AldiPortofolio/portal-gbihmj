<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="format-detection" content="telephone=no" />
<meta http-equiv="Content-Language" content="id">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, minimal-ui"/>
<title>{{$meta['title']}} - {{$web_title}}</title>

<!-- <meta content="{{$meta['keywords']}}" name="keywords" /> -->
<meta content="{{$meta['description']}}" name="description" />
<meta name="geo.placename" content="Indonesia">
<meta name="geo.country" content="ID">
<meta name="language" content="Indonesian">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="root_url" content="{{url($root_path)}}/" />
<meta name="robots" content="{{$meta_robots}}">

<!-- “canonical link”, link resmi  -->
<link rel="canonical" href="{{url($canonical)}}" />

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="{{asset('components/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
<link rel ="stylesheet" href="{{asset('components/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
<link rel ="stylesheet" href="{{asset('components/plugins/bootstrap/css/bootstrap.min.css')}}">
<link href="{{asset('components/plugins/uniform/css/uniform.default.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('components/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME GLOBAL STYLES -->
<link rel = "stylesheet" href="{{asset('components/front/css/metronic/components.min.css')}}" type="text/css">
<link rel = "stylesheet" href="{{asset('components/front/css/metronic/components-md.min.css')}}" type="text/css">
<link href="{{asset('components/front/css/metronic/plugins.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->
<link rel ="stylesheet" href="{{asset('components/plugins/swiper-slider/swiper.min.css')}}">
<!-- BEGIN THEME LAYOUT STYLES -->
<link rel ="stylesheet" href="{{asset('components/front/css/layout.min.css')}}">
<link rel ="stylesheet" href="{{asset('components/front/css/metronic/themes/default.css')}}">
<link rel ="stylesheet" href="{{asset('components/front/css/custom.css')}}">
<!-- END THEME LAYOUT STYLES -->

@stack('css')
<link rel = "stylesheet" href="{{asset('components/plugins/jquery-ui/jquery-ui.min.css')}}" type="text/css">
<link href="{{asset('components/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('components/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('components/plugins/sweetalert2/sweet-alert.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('components/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('components/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('components/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('components/plugins/bootstrap-colorpicker/css/colorpicker.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{asset('components/plugins/cropper/cropper.min.css')}}">
<!-- <link rel = "stylesheet" href="{{asset('components/back/css/style.css')}}" type="text/css"> -->
<!-- <link rel = "stylesheet" href="{{asset('components/back/css/pages/profile-2.min.css')}}" type="text/css"> -->
@stack('custom_css')

<!-- <link rel ="stylesheet" href="{{asset('components/front/css/custom_style.css')}}"> -->