@extends($view_path.'.layouts.master')
@section('content')
<!-- <form role="form" method="post" action="{{url($path)}}/" enctype="multipart/form-data"> -->
  {{ method_field('PUT') }}
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption font-green">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
      </div>
      <div class="actions">
        <div class="actions">
          <div class="head-button">
          <!-- <a href="http://localhost/Dash-Backend/_admin/notification/inbox/ext/export?type=excel" class="font-dash font-16 margin-left-20">
          <i class="fa fa-file-excel-o fa-lg"></i></a> -->
          <a class="font-dash font-16 search-head margin-left-20"><i class="fa fa-search fa-lg"></i></a>&nbsp;&nbsp;&nbsp;
          <a href="{{url($path)}}"><button type="button" class="btn red-mint">{{trans('general.back')}}</button></a>
        </div>
        </div>
      </div>
    </div>
    <div class="portlet-body form">
      <form method="get" id="builder_form" action="{{url($path).'/'.$id_page}}" data-ask="n"></form>
      <div class="table-responsive">
        <table class="table table-bordered">
          <tbody>
            <tr class="search-head-content" style="{{Request::has('q') ? '' : 'display:none;'}}">
              <th></th>
              <th>
                <div class="form-group form-md-line-input">
                <input type="text" placeholder="From" id="form_floating_1" class="form-control search-data " name="from" value=""></div>
              </th>
              <th>
                <div class="form-group form-md-line-input">
                  <input type="text" placeholder="To" id="form_floating_2" class="form-control search-data " name="to" value="">
                </div>
              </th>
              <th>
                <div class="form-group form-md-line-input">
                <input type="text" placeholder="Subject" id="form_floating_3" class="form-control search-data " name="subject" value=""></div>
              </th>
              <th colspan="3"><button type="submit" class="btn green submit-search"> <i class="fa fa-search"></i> Filter</button>
                <a href="{{url($path).'/'.$id_page}}" class="btn green red-mint">
                  <i class="fa fa-refresh"></i> Reset
                </a>
              </th>
            </tr>
            <tr>
              <th>No</th>
              <th>From</th>
              <th>To</th>
              <th>Subject</th>
              <th></th>
            </tr>
            @foreach($inbox as $q => $ibx)
            <tr>
              <td class="vcenter">{{ $q+1 }}</td>
              <td class="vcenter">{{ $ibx->name }}</td>
              <td class="vcenter">
                @php
                  $to = json_decode($ibx->to_user_id);
                @endphp

                {{ $to[0] }}
              </td>
              <td class="vcenter">{{ $ibx->subject }}</td>
              <td class="vcenter">
                <a href="{{ url($path) }}/{{ $ibx->sender }}/{{ $ibx->ibx_id }}" class="btn green green-jungle">
                  View Message
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <center>{{ $inbox->links() }}</center>
    </div>
  </div>
<!-- </form> -->
@endsection
