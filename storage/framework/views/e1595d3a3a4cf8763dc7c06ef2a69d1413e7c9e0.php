
<?php $__env->startSection('content'); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
<!-- <script src="<?php echo e(asset('components/plugins/amcharts/amcharts/amcharts.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/amcharts/amcharts/serial.js')); ?>"></script>
<script src="<?php echo e(asset('components/plugins/amcharts/amcharts/themes/light.js')); ?>"></script> -->
<!-- Load the JavaScript API client and Sign-in library. -->
<!-- <script src="https://apis.google.com/js/client:platform.js"></script> -->

<script src="//cdn.amcharts.com/lib/3/amcharts.js"></script>
<script src="//cdn.amcharts.com/lib/3/serial.js"></script>
<script src="//cdn.amcharts.com/lib/3/themes/light.js"></script>

<?php $__env->startPush('styles'); ?>

<?php $__env->stopPush(); ?>
<!-- <div class="row">
	<div class="col-md-12 dashboard coming-soon">
		<img class="img-responsive" src="<?php echo e(asset('components/back/images/admin/dashboard.jpg')); ?>">
	</div>
</div> -->
<?php $__env->startPush('css'); ?>
<style type="text/css">
	.panel-heading .fa{
		line-height: initial;
	}
	#chartdiv3, #chartdiv4 {
		width: 100%;
		height: 200px;
	}
</style>
<?php $__env->stopPush(); ?>

<div class="row">
	<div class="col-lg-3 col-md-6 total_church">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-home fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo e($church->total); ?></div>
						<div>Total Church</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 total_active_user">
		<div class="panel panel-success">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-support fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo e($user->total); ?></div>
						<div>Total Active Users</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-support fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo e($event->total); ?></div>
						<div>Total Active Events</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-support fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo e($seat->total_seat); ?></div>
						<div>Total Active Seats</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-support fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo e($room->total_room); ?></div>
						<div>Total Active Rooms</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-support fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo e($article->total); ?></div>
						<div>Total Active Articles</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-success">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-support fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo e($church->music); ?></div>
						<div>Total Musics</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-support fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo e($booking_oneMonthAgo->total); ?></div>
						<div>Total Booking 1 Month Ago</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
</div>

<div class="row">
<div id="chartdiv3"></div>
</div>
<?php $__env->startPush('scripts'); ?>
<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		// let data_booking = [
		// 	{
		// 		"created_at": "2016-05-16",
		// 		"total_booking": 8,
		// 	}, {
		// 		"created_at": "2016-05-17",
		// 		"total_booking": 5,
		// 	}, {
		// 		"created_at": "2016-05-18",
		// 		"total_booking": 6,
		// 	}, {
		// 		"created_at": "2016-05-19",
		// 		"total_booking": 7,
		// 	}, {
		// 		"created_at": "2016-05-20",
		// 		"total_booking": 6,
		// 	}, {
		// 		"created_at": "2016-05-21",
		// 		"total_booking": 3,
		// 	}
		// ];
		let data_booking = <?php echo $booking_oneWeekAgo;?>;

        var chart = AmCharts.makeChart("chartdiv3", {
		"type": "serial",
		"categoryField": "created_at",
		"titles": [{
			"text": "Data Booking 1 Week Ago"
		}],
		"dataDateFormat": "YYYY-MM-DD",
		"categoryAxis": {
			"parseDates": true
		},
		"graphs": [{
			"bullet": "round",
			"lineThickness": 3,
			"title": "Completed Orders",
			"type": "smoothedLine",
			"valueField": "total_booking"
		}],
		"guides": [],
		"allLabels": [],
		"dataProvider": data_booking

	});

/**
 * ---------------------------------------
 * This demo was created using amCharts 4.
 *
 * For more information visit:
 * https://www.amcharts.com/
 *
 * Documentation is available at:
 * https://www.amcharts.com/docs/v4/
 * ---------------------------------------
 */

// // Create chart instance
// var chart = am4core.create("chartdiv4", am4charts.XYChart);

// chart.titles.template.fontSize = 20;
// chart.titles.create().text = "amCharts 4";

// // Add data
// chart.data = [{
//     "date": "2016-05-16",
//     "completed_orders_count": 8,
//   }, {
//     "date": "2016-05-17",
//     "completed_orders_count": 5,
//   }, {
//     "date": "2016-05-18",
//     "completed_orders_count": 6,
//   }, {
//     "date": "2016-05-19",
//     "completed_orders_count": 7,
//   }, {
//     "date": "2016-05-20",
//     "completed_orders_count": 6,
//   }, {
//     "date": "2016-05-21",
//     "completed_orders_count": 3,
//   }, {
//     "date": "2016-05-22",
//     "completed_orders_count": 8,
//   }, {
//     "date": "2016-05-23",
//     "completed_orders_count": 3,
//   }, {
//     "date": "2016-05-24",
//     "completed_orders_count": 10,
//   }, {
//     "date": "2016-05-25",
//     "completed_orders_count": 7,
//   }, {
//     "date": "2016-05-26",
//     "completed_orders_count": 4,
//   }, {
//     "date": "2016-05-27",
//     "completed_orders_count": 3,
//   }, {
//     "date": "2016-05-28",
//     "completed_orders_count": 5,
//   }, {
//     "date": "2016-05-29",
//     "completed_orders_count": 8,
//   }, {
//     "date": "2016-05-30",
//     "completed_orders_count": 9,
//   }, {
//     "date": "2016-05-31",
//     "completed_orders_count": 10,
//   }, {
//     "date": "2016-06-01",
//     "completed_orders_count": 10,
//   }, {
//     "date": "2016-06-02",
//     "completed_orders_count": 3,
//   }, {
//     "date": "2016-06-03",
//     "completed_orders_count": 6,
//   }, {
//     "date": "2016-06-04",
//     "completed_orders_count": 3,
//   }, {
//     "date": "2016-06-05",
//     "completed_orders_count": 10,
//   }, {
//     "date": "2016-06-06",
//     "completed_orders_count": 3,
//   }, {
//     "date": "2016-06-07",
//     "completed_orders_count": 6,
//   }, {
//     "date": "2016-06-08",
//     "completed_orders_count": 0,
//   }, {
//     "date": "2016-06-09",
//     "completed_orders_count": 0,
//   }, {
//     "date": "2016-06-10",
//     "completed_orders_count": 0,
//   }, {
//     "date": "2016-06-11",
//     "completed_orders_count": 0,
//   }, {
//     "date": "2016-06-12",
//     "completed_orders_count": 0,
//   }, {
//     "date": "2016-06-13",
//     "completed_orders_count": 0,
//   }, {
//     "date": "2016-06-14",
//     "completed_orders_count": 4,
//   }];

// chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

// // Create axes
// var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

// // Create value axis
// var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// // Create series
// var series = chart.series.push(new am4charts.LineSeries());
// series.dataFields.valueY = "completed_orders_count";
// series.dataFields.dateX = "date";
// series.name = "Completed Orders";
// series.strokeWidth = 3;
// series.tensionX = 0.7;
// //series.tensionY = 0.9;
// series.bullets.push(new am4charts.CircleBullet());
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($view_path.'.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>