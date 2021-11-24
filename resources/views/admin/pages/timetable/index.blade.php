@extends($view_path.'.layouts.master')
@push('css')
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="{{asset('components/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('components/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('components/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('components/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('components/plugins/clockface/css/clockface.css')}}" rel="stylesheet" type="text/css" />
  <!-- END PAGE LEVEL PLUGINS -->

  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="{{asset('components/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('components/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- END PAGE LEVEL PLUGINS -->

  <style type="text/css">
      .table.table-light > tbody > tr > td, {
        border: 1px solid #ddd;
      }

      .table.table-light > tbody > tr > td {
        border: 1px solid #ddd !important;
      }

      .table>thead:first-child>tr:first-child>th {
        border: 1px solid #ddd !important;
      }

      #header-fixed { 
          position: fixed; 
          top: 100px; display:none;
          background-color:white;
      }
  </style>
@endpush
@section('content')
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption font-green">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
      </div>
    </div>
    <div class="portlet-body form">
      <form role="form" class="form-horizontal">
        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group form-md-line-input">
                <label class="control-label col-md-4">Tanggal</label>
                <div class="col-md-8">
                    <div id="reportrange" class="btn default">
                        <i class="fa fa-calendar"></i> &nbsp;
                        <span> </span>
                        <b class="fa fa-angle-down"></b>
                    </div>
                </div>
              </div>
            </div>

            <!-- <div class="col-md-6">
              <div class="form-group form-md-line-input">
                <label class="col-md-4 control-label" for="form_control_1">Order Status</label>
                <div class="col-md-8">
                    <select name="order_status" id="order_status" class="form-control select2-multiple" multiple>
                      <optgroup label="Product">
                        <option value="0">-- All Order Status --</option>
                        @foreach($order_status as $p)
                          <option value="{{$p->id}}">{{$p->order_status_name}}</option>
                        @endforeach
                      </optgroup>
                    </select>
                    <div class="form-control-focus"> </div>
                </div>
              </div>
            </div>

             <div class="col-md-6">
              <div class="form-group form-md-line-input">
                <label class="col-md-4 control-label" for="form_control_1">Promo Code</label>
                <div class="col-md-8">
                    <select name="promo_code" id="promo_code" class="form-control select2-multiple" multiple>
                      <optgroup label="Promo Code">
                        <option value="0">-- All Promo Code --</option>
                        @foreach($promo_code as $p)
                          <option value="{{$p->id}}">{{$p->voucher_code}}</option>
                        @endforeach
                      </optgroup>
                    </select>
                    <div class="form-control-focus"> </div>
                </div>
              </div>
            </div>

             <div class="col-md-6">
              <div class="form-group form-md-line-input">
                <label class="col-md-4 control-label" for="form_control_1">Agent</label>
                <div class="col-md-8">
                    <select name="user_id" id="user_id" class="form-control select2-multiple" multiple>
                      <optgroup label="Agent Name">
                        <option value="0">-- All Agent --</option>
                        @foreach($agent_name as $p)
                          <option value="{{$p->id}}">{{$p->name.' - '.$p->marketing}}</option>
                        @endforeach
                      </optgroup>
                    </select>
                    <div class="form-control-focus"> </div>
                </div>
              </div>
            </div> -->
          </div>

          <div class="row">
            <div class="cold-md-12 text-center">
              <input type="button" id="search" value="Cari" class="btn btn-success">
              <input type="button" id="export" value="Export to excel" class="btn btn-info">
              <a id="expt" class="hidden">{{trans('general.export')}}</a>
            </div>
          </div>

          <br>
        
          <div class="row">
            <div id="report-content" class="col-md-12">
              
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
@push('custom_scripts')
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <script src="{{asset('components/plugins/moment.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('components/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('components/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('components/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('components/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('components/plugins/clockface/js/clockface.js')}}" type="text/javascript"></script>
  <!-- END PAGE LEVEL PLUGINS -->

  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <script src="{{asset('components/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
  <!-- END PAGE LEVEL PLUGINS -->

  <script>
    jQuery(document).ready(function() {
      $('#reportrange').daterangepicker({
        opens: (App.isRTL() ? 'left' : 'right'),
        startDate: moment().subtract('days', 29),
        endDate: moment(),
        dateLimit: {
            days: 60
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'Last 7 Days': [moment().subtract('days', 6), moment()],
            'Last 30 Days': [moment().subtract('days', 29), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        buttonClasses: ['btn'],
        applyClass: 'green',
        cancelClass: 'default',
        format: 'DD-MM-YYYY',
        separator: ' to ',
        locale: {
            applyLabel: 'Apply',
            format: 'DD-MM-YYYY',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom Range',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
      },
        function (start, end) {
            $('#reportrange span').html(start.format('DD-MM-YYYY') + ' s/d ' + end.format('DD-MM-YYYY'));
        }
      );
      //Set the initial state of the picker label
      $('#reportrange span').html(moment().subtract('days', 29).format('DD-MM-YYYY') + ' s/d ' + moment().format('DD-MM-YYYY'));

      //select 2
      $(".select2-multiple").select2({
          placeholder: "Klik di sini",
          width: null
      });

      $(".select2-multiple").on("select2:open", function() {
          if ($(this).parents("[class*='has-']").length) {
              var classNames = $(this).parents("[class*='has-']")[0].className.split(/\s+/);

              for (var i = 0; i < classNames.length; ++i) {
                  if (classNames[i].match("has-")) {
                      $("body > .select2-container").addClass(classNames[i]);
                  }
              }
          }
      });

      $("#search").on("click", function() {
        console.log('search');
          // var searchProduct   = $("#order_status").select2('data');
          // var promoId         = $("#promo_code").select2('data');
          // var userId          = $("#user_id").select2('data');
          var searchDate      = $("#reportrange span").html();

          var arr = [];
          var arr2 = [];
          var arr3 = [];

          var split = searchDate.split(" ");
          var searchDateFrom = split[0];
          var searchDateTo = split[2];

          // for(var i in searchProduct){
          //     var getOption = searchProduct[i].id;
          //     // var option = getOption[0];

          //     arr.push(getOption);
          // }

          // for(var i in promoId){
          //     var getOption = promoId[i].id;
          //     // var option = getOption[0];

          //     arr2.push(getOption);
          // }

          // for(var i in userId){
          //     var getOption = userId[i].id;
          //     // var option = getOption[0];

          //     arr3.push(getOption);
          // }

          $.ajax({
              type: "POST",
              url: $.cur_url() + "/ext/filter",
              data: {search_date_from: searchDateFrom, search_date_to: searchDateTo},
          }).done(function(msg) {
            $("#report-content").html(msg);
          });
      });

    });

    $(document).on('click','#export',function(){
        var searchDate = $("#reportrange span").html();
        tableToExcel('table-laporan', 'table-laporan', 'Laporan Stock Outlet-'+searchDate.replace(" ", "-")+'.xls');
    });
  </script>
@endpush