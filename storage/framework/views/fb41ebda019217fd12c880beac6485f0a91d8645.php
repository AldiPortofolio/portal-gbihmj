<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $__env->make($view_path.'.includes.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	</head>
	<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo page-sidebar-fixed page-md">
		<?php echo $__env->make($view_path.'.includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="row">
 					<div class="col-md-12">
						<?php echo $breadcrumb; ?>

						<?php echo $__env->yieldContent('content'); ?>
					</div>
				</div>
		    <div class="clearfix"></div>
		  </div>
		</div>
		<?php echo $__env->make($view_path.'.includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div id="overlay" style="display:none;">
			<svg class="circular" viewBox="25 25 50 50">
		      <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
		    </svg>
		</div>
		<div class="sk-cube-grid" style="display: none;">
		  <div class="sk-cube sk-cube1"></div>
		  <div class="sk-cube sk-cube2"></div>
		  <div class="sk-cube sk-cube3"></div>
		  <div class="sk-cube sk-cube4"></div>
		  <div class="sk-cube sk-cube5"></div>
		  <div class="sk-cube sk-cube6"></div>
		  <div class="sk-cube sk-cube7"></div>
		  <div class="sk-cube sk-cube8"></div>
		  <div class="sk-cube sk-cube9"></div>
		</div>
	</body>
</html>