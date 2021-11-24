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
      .table-heading{
        border: 1px solid #ddd !important;
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

              <div class="form-group form-md-line-input">
                <label class="col-md-4 control-label" for="form_control_1">Outlet</label>
                <div class="col-md-8">
                    <select class="form-control" id="outlet">
                        <option value="">-- Please Select Outlet --</option>
                        @foreach($outlet as $u)
                          <option value="{{$u->id}}">{{$u->outlet_name}}</option>
                        @endforeach
                    </select> 
                    <div class="form-control-focus"> </div>
                </div>
              </div>

              <div class="form-group form-md-line-input">
                <label class="col-md-4 control-label" for="form_control_1">Operator</label>
                <div class="col-md-8">
                    <input type="hidden" value="{{$operator}}" id="operator_arr">
                    <select id="operator" class="form-control" disabled="">
                        <option value="">-- Please Select Operator --</option>
                        @foreach($operator as $o)
                          <option value="{{$o->id}}" id="outlet_id-{{$o->id}}">{{$o->name}}</option>
                        @endforeach
                    </select>
                    <div class="form-control-focus"> </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              
            </div>
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

      $('#outlet').change(function(){
          var val     = $('#outlet').val();

          $("#operator").children('option').hide();
          $("#operator").removeAttr("disabled");

          var operator_arr = JSON.parse($('#operator_arr').val());
          if(operator_arr.length > 0){
            for(var i=0; i<operator_arr.length; i++){
                var outlet_id   =  operator_arr[i]['outlet_id'];
                outlet_id = $.formatSeparatorToArray(outlet_id, ';');
                if(outlet_id.length > 0){
                    for(var j=0; j<outlet_id.length; j++){
                        if(outlet_id[j] == val){
                            $("#operator").children('option[value^="'+operator_arr[i]['id']+'"]').show();
                        }
                    }
                }
            }
          }
      });

      $("#search").on("click", function() {
          var searchOutlet    = $("#outlet").val();
          var searchOperator  = $("#operator").val();
          var searchDate      = $("#reportrange span").html();

          var split = searchDate.split(" ");
          var searchDateFrom = split[0];
          var searchDateTo = split[2];

          

          $.ajax({
              type: "POST",
              url: $.cur_url() + "/ext/filter",
              data: { outlet_id: searchOutlet, operator_id: searchOperator, search_date_from: searchDateFrom, search_date_to: searchDateTo},
          }).done(function(msg) {
            $("#report-content").html(msg);
          });
      });

    });

    $(document).on('click','#export',function(){
        var searchDate = $("#reportrange span").html();
        tableToExcel('table-laporan', 'table-laporan', 'outlet_income_report-'+searchDate.replace(" ", "-")+'.xls');
    });
  </script>
@endpush