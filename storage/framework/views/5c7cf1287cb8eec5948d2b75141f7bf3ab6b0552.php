<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo e($web_name); ?> - Admin Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="id">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="<?php echo e($web_description); ?>" name="description" />
    <meta content="KoPosIndo" name="author" />
    <meta name="geo.placename" content="Indonesia">
    <meta name="geo.country" content="ID">
    <meta name="language" content="Indonesian">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <meta name="root_url" content="<?php echo e(url($root_path)); ?>/" />

    <!-- CSS -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    
    <link rel="icon" href="<?php echo e(asset('components/both/images/web/')); ?>/<?php echo e($favicon); ?>" type="image/x-icon">
    <link rel = "stylesheet" href="<?php echo e(asset('components/plugins/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css">
    <link rel = "stylesheet" href="<?php echo e(asset('components/plugins/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css">
    <link rel = "stylesheet" href="<?php echo e(asset('components/plugins/uniform/css/uniform.default.css')); ?>" rel="stylesheet" type="text/css">
    <link rel = "stylesheet" href="<?php echo e(asset('components/back/css/components-md.min.css')); ?>" rel="stylesheet" type="text/css">
    <link rel = "stylesheet" href="<?php echo e(asset('components/back/css/plugins-md.min.css')); ?>" rel="stylesheet" type="text/css">
    <link rel = "stylesheet" href="<?php echo e(asset('components/back/css/style.css')); ?>" rel="stylesheet" type="text/css">
</head>
<body class="login">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-4 col-md-4">
                <div class="col-md-12 text-center login-logo">
                    <img class="img-responsive" src="<?php echo e(asset('components/back/images/admin')); ?>/<?php echo e($web_logo); ?>" />
                    <!-- <h2 style="text-align: center; font-weight: bold;">KoPosIndo</h2> -->
                </div>
                <div class="col-md-12">
                    <form action="<?php echo e(url($root_path.'/cek_login')); ?>" class="login-form" method="post">
                        <?php echo $__env->make($view_path.'.includes.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        <div class="growl-alert" data-type="info" data-message="Welcome to the login page"></div>
                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" placeholder="Email" name="email" autofocus required/>
                            
                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" required/>
                        
                        <div class="text-center">
                            <button class="btn blue form-control" type="submit">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('components/plugins/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('components/plugins/bootstrap/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('components/plugins/jquery-slimscroll/jquery.slimscroll.min.js')); ?>"></script>
    <script src="<?php echo e(asset('components/plugins/backstretch/jquery.backstretch.min.js')); ?>"></script>
    <script src="<?php echo e(asset('components/plugins/uniform/jquery.uniform.min.js')); ?>"></script>
    <script src="<?php echo e(asset('components/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js')); ?>"></script>
    <script src="<?php echo e(asset('components/back/js/app.min.js')); ?>"></script>
    <script src="<?php echo e(asset('components/back/js/kyubi.js')); ?>"></script>
    <script>
        $(document).ready(function(){
            $.initlogin();
        });
    </script>
  </body>
</html>