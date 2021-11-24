@extends($view_path.'.layouts.master')
@push('css')
    <link href="{{asset('components/back/css/pages/profile-2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

@if(Request::get('histori'))
    <input type="hidden" id="page_tabs" />
@endif

<div class="profile">
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

        <div class="tabbable-line tabbable-full-width">
            <ul class="nav nav-tabs" id="tb_cus">
                <li class="active">
                    <a href="#tab_1_1" data-toggle="tab"> Overview </a>
                </li>

                <li>
                    <a href="#tab_2_1" data-toggle="tab"> Histori Posting </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="list-unstyled profile-nav">
                                    <li>
                                        <img src="{{asset('components/both/image/customer')}}/{{ $data1->id }}/{{ $data1->images }}" onerror="this.src='{{asset('components/admin/image/default.jpg') }}';" class="img-responsive pic-bordered" alt="" />
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-8 profile-info">
                                        <h1 class="font-green sbold uppercase">{{ $data1->name ? $data1->name : '' }}</h1>
                                        <p>
                                            <i class="fa fa-calendar"></i> {{ date('d - m - Y', strtotime($data1->birth_date)) }}
                                        </p>
                                        <p>
                                            <i class="fa fa-envelope"></i> {{ $data1->email ? $data1->email : '' }}
                                        </p>
                                        <p>
                                            <i class="fa fa-phone"></i> {{ $data1->phone ? $data1->phone : '' }}  
                                        </p>
                                        <p>
                                            <i class="fa {{$data1->gender == 'l' ? 'fa-male' : 'fa-female'}}"></i> {{$data1->gender == "l" ? "Male" : "Female"}}  
                                        </p>
                                        <p>
                                            <i class="fa fa-building"></i> {{ $data1->address ? $data1->address : '' }}
                                        </p>
                                    </div>
                                </div>
                                <!--end row-->
                            </div>
                        </div>

                        @php
                            $gallery = json_decode($data1->gallery);
                        @endphp

                        @if(count($gallery) > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="portlet-title">
                                    <div class="caption font-green portlet-container">
                                        <i class="icon-layers font-green title-icon"></i>
                                        <span class="caption-subject bold uppercase"> Gallery</span>
                                        <div class="head-button">
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>

                           
                            <div class="col-md-12">
                                <div class="row">
                                    @foreach($gallery as $gal)
                                    <div class="col-md-3 col-sm-3">
                                        <img src="{{asset($image_path.'/'.$gal)}}" onerror="this.src='{{asset($image_path2.'/'.'none.png') }}';" alt="" class="img-responsive"><br>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="tab-pane" id="tab_2_1">
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                                <tr>
                                    <th><b>Title</b></th>
                                    <th><b>Event</b></th>
                                    <th><b>Estimate Price</b></th>
                                    <th><b>Event Date</b></th>
                                    <th><b>Status</b></th>
                                    <th><b>Action</b></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data2 as $dt2)
                                <tr>
                                    <td>{{ $dt2->title }}</td> 
                                    <td>{{ $dt2->event }}</td>
                                    <td>Rp. {{ number_format($dt2->estimate_price) }}</td>
                                    <td>
                                        @php 
                                            $date = date_create($dt2->event_date);
                                        @endphp

                                        {{ date_format($date,"d - m - Y") }}
                                    </td>
                                    <td>{{ $dt2->desc }}</td>
                                    <td>
                                        <a href="{{ url($path) }}/posting/{{ $data1->id }}/{{ $dt2->id }}" class="btn green green-jungle">
                                          Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="text-center">{{ $data2->links() }}</div>
                    </div>
                </div>
                <!--end tab-pane-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom_scripts')
<script>
$(document).ready(function(){
    if($('#page_tabs').length > 0){
      $('#tb_cus a[href="#tab_2_1"]').tab('show');
    }
});
</script>
@endpush