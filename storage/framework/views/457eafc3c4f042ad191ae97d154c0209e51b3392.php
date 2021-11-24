<meta charset="utf-8" />
<title><?php echo e($title); ?> - Admin Panel</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="id">
<meta content="width=device-width, initial-scale=1" name="viewport" />

<meta content="<?php echo e($web_keywords); ?>" name="keywords" />
<meta content="<?php echo e($web_description); ?>" name="description" />
<meta content="" name="author" />
<meta name="geo.placename" content="Indonesia">
<meta name="geo.country" content="ID">
<meta name="language" content="Indonesian">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<meta name="root_url" content="<?php echo e(url($root_path)); ?>/" />
<!-- <meta name="google-signin-client_id" content="473142885068-4e0jqqc933dohpafj6s6q00e12er16lp.apps.googleusercontent.com"> -->
<!-- <meta name="google-signin-scope" content="https://www.googleapis.com/auth/analytics.readonly"> -->

<!-- Core CSS -->
<link rel="icon" href="<?php echo e(asset('components/back/images/admin')); ?>/<?php echo e($favicon); ?>" type="image/x-icon">
<!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" /> -->

<link rel = "stylesheet" href="<?php echo e(asset('../components/plugins/font-awesome/css/font-awesome.min.css')); ?>" type="text/css">
<link rel = "stylesheet" href="<?php echo e(asset('components/plugins/bootstrap/css/bootstrap.min.css')); ?>" type="text/css">
<link rel = "stylesheet" href="<?php echo e(asset('components/back/css/components-md.min.css')); ?>" type="text/css">
<link rel = "stylesheet" href="<?php echo e(asset('components/back/css/plugins-md.min.css')); ?>" type="text/css">
<link rel = "stylesheet" href="<?php echo e(asset('components/back/css/layout.min.css')); ?>" type="text/css">
<link rel = "stylesheet" href="<?php echo e(asset('components/back/css/light.min.css')); ?>" type="text/css">
<?php echo $__env->yieldPushContent('css'); ?>
<link rel = "stylesheet" href="<?php echo e(asset('components/plugins/jquery-ui/jquery-ui.min.css')); ?>" type="text/css">
<link href="<?php echo e(asset('components/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('components/plugins/select2/css/select2-bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('components/plugins/sweetalert2/sweet-alert.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('components/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('components/plugins/bootstrap-daterangepicker/daterangepicker.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('components/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('components/plugins/bootstrap-colorpicker/css/colorpicker.css')); ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('components/plugins/cropper/cropper.min.css')); ?>">
<link rel = "stylesheet" href="<?php echo e(asset('components/back/css/style.css')); ?>" type="text/css">
<link rel = "stylesheet" href="<?php echo e(asset('components/back/css/pages/profile-2.min.css')); ?>" type="text/css">
<?php echo $__env->yieldPushContent('custom_css'); ?>
