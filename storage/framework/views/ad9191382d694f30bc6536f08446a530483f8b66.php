<div class="page-footer">
    <!-- <div class="page-footer-inner"> 2017 - <?php echo e(date('Y')); ?> &copy; Copyright by 
    	<a href="<?php echo e(url('http://jetfi.co.id/')); ?>">Jetfi</a>
    </div> -->
    <div class="scroll-to-top">
        <i class="fa fa-arrow-circle-up"></i>
    </div>
</div>

<!--Core JS -->
<script src="<?php echo e(asset('components/plugins/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/jquery-ui/jquery-ui.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/bootstrap/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/jquery-slimscroll/jquery.slimscroll.min.js')); ?>"></script>
<!-- Custom JS -->
<script src="<?php echo e(asset('components/plugins/tinymce/js/tinymce/tinymce.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/select2/js/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/bootstrap-daterangepicker/daterangepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/counterup/jquery.waypoints.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('components/plugins/counterup/jquery.counterup.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('components/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo e(asset('components/plugins/cropper/cropper.min.js')); ?>"></script>  
<script src="<?php echo e(asset('components/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/sweetalert2/sweet-alert.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/back/js/app.min.js')); ?>"></script>
<script src="<?php echo e(asset('components/back/js/layout.min.js')); ?>"></script>

<!-- pusher notification -->
<script src="https://js.pusher.com/4.3/pusher.min.js"></script>
<!-- Enable pusher logging - don't include this in production -->
<script type="text/javascript">
    // Pusher.logToConsole = true;

    // var pusher = new Pusher('aec2592ecc4afa736616', {
    //   cluster: 'ap1',
    //   forceTLS: true
    // });

    // var channel = pusher.subscribe('my-channel');
    // channel.bind('my-event', function(data) {
    //   alert(JSON.stringify(data));
    // });
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
<script src="<?php echo e(asset('components/back/js/kyubi.js?v=2')); ?>"></script>
<!-- <script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-102907116-1', 'auto');
		ga('send', 'pageview');
		console.log(ga.q);
		console.log(location.pathname);
</script> -->
<?php echo $__env->yieldPushContent('custom_scripts'); ?>