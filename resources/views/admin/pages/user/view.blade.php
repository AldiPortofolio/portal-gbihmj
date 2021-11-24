@extends($view_path.'.layouts.master')
@push('css')
	<link href="{{asset('components/back/css/pages/profile-2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="profile">
    <div class="tabbable-line tabbable-full-width">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab"> Overview </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1_1">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-unstyled profile-nav">
                            <li>
                                @if($user->images != null && file_exists($image_path.$user->images))
                                    <img src="{{asset($image_path.$user->images)}}" class="img-responsive pic-bordered" alt="" />
                                @else
                                    <img src="{{asset('components/admin/image/default.jpg')}}" class="img-responsive pic-bordered" alt="" />
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-8 profile-info">
                                <h4 class="font-green sbold">{{$user->name}} ({{$user->useraccess->access_name}})</h4>
                                <p>
                                	{{$ouser}}
                                </p>
                                <ul class="list-inline">
                                    <li>
                                        <i class="fa fa-calendar"></i> {{date( 'd M Y', strtotime($user->created_at) )}} </li>
                                    <li>
                                        <i class="fa fa-user"></i> {{$user->username}} </li>
                                    <li>
                                        <i class="fa fa-envelope"></i> {{$user->email}} </li>
                                </ul>
                            </div>
                            <!--end col-md-8-->
                        </div>
                        
                    </div>
                </div>
            </div>
            <!--end tab-pane-->
        </div>
    </div>
</div>
@endsection
@push('custom_scripts')

@endpush