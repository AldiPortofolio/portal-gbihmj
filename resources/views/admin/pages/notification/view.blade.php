@extends($view_path.'.layouts.master')
@push('css')
	<link href="{{asset('components/back/css/pages/profile-2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

<div class="portlet light bordered">    
    <div class="portlet-title">
        <div class="caption font-green">
            <i class="icon-layers font-green title-icon"></i>
            <span class="caption-subject bold uppercase"> {{$title}}</span>
        </div>
        <div class="actions">
            <a href="{{url($path)}}"><button type="button" class="btn red-mint">{{trans('general.back')}}</button></a>
        </div>
    </div>
        <div class="portlet-body form">
            <div class="tabbable-line">
            <div class="tab-pane active" id="tab_1_1">
                <div class="row">
                    <div class="col-md-9">
                            <!-- <div class="col-md-8 profile-info"> -->
                            <table class="table table-striped">
                                <tr>
                                    <td>Title</td>
                                    <td>{{$data->notification_title}}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{date( 'd M Y', strtotime($data->created_at) )}}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{$data->type}}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>{{$data->description}}</td>
                                </tr>
                                <tr>
                                    <td>Order No.</td>
                                    <td><a href="{{url('_admin/order/manage-order/'.$data->order_id.'/edit')}}">{{$data->order_no}}</td>
                                </tr>        
                            </table>
                            <!-- </div> -->
                            <!--end col-md-8-->                        
                    </div>
                </div>
            </div>
            <!--end tab-pane-->
            </div>
        </div>
@endsection
@push('custom_scripts')

@endpush