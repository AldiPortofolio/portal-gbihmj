<div class="page-header navbar navbar-fixed-top">
  <div class="page-header-inner ">
    <div class="page-logo">
        <a href="{{url($root_path)}}">
          <img src="{{asset('components/back/images/admin')}}/{{$web_logo}}" alt="logo" class="logo-default" />
        </a>
        <div class="menu-toggler sidebar-toggler"></div>
    </div>
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
    <div class="page-top">
      <div class="top-menu">
        <ul class="nav navbar-nav pull-right">
          <li class="dropdown dropdown-user dropdown-dark">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
              <span class="username"> {{ucwords(auth()->guard($guard)->user()->username)}} </span>
              <img alt="" class="img-circle" src="{{asset('components/admin/image/user')}}/{{auth()->guard($guard)->user()->images ? auth()->guard($guard)->user()->images : '../../../admin/image/default.jpg'}}" /> 
            </a> 
            <ul class="dropdown-menu dropdown-menu-default">
              <li>
                <a href="{{url($root_path.'/profile')}}">
                <i class="fa fa-user"></i>{{trans('general.profile')}}</a>
              </li>
              <li>
                <a href="{{url($root_path.'/logout')}}">
                <i class="fa fa-key"></i> {{trans('general.sign_out')}} </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
          <div class="page-top">
      <div class="top-menu">
        <ul class="nav navbar-nav pull-right">
          <li class="dropdown dropdown-user dropdown-dark">
            <!-- <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <i class="fa fa-bell-o" style="font-size: 20px;"></i>
               
            </a>  -->
            <ul class="dropdown-menu dropdown-menu-default" style="max-height: 400px;overflow-y: scroll;">
               <li class="external">
                  <h3 class="notif_title">Notification</h3>
                  <a class="view-all" href="{{url($root_path.'/notification/manage-notification')}}">view all</a>
              </li>
             
              <!-- <li>
                <a href="{{url($root_path.'/logout')}}">
                <i class="fa fa-key"></i> {{trans('general.sign_out')}} </a>
              </li> -->
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"> </div>
<div class="page-container">
  <div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
      <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        @for ($i = 0;$i < count($authmenu); $i++)
          @if($authmenu[$i]->heading)
            <li class="heading">
              <h3 class="uppercase">{{trans('general.'.strtolower($authmenu[$i]->heading))}}</h3>
            </li>
          @endif
          @if (count($msmenu[$i]) == 0)
            <li class="nav-item start {{$authmenu[$i]->menu_name_alias == '/' ? (Request::path() == $root_path ? 'active' : '') : Request::path() == $root_path.'/'.$authmenu[$i]->menu_name_alias || Request::is($root_path.'/'.$authmenu[$i]->menu_name_alias.'/*') ? 'active' : ''}}">
              <a href="{{url($root_path.'/'.$authmenu[$i]->menu_name_alias.'')}}" class="nav-link ">
                <i class="{{$authmenu[$i]->icon}}"></i>
                <span class="title">{{trans('general.'.$authmenu[$i]->menu_name_alias)}}</span>
                <span class="selected"></span>
              </a>
            </li>
          @else
            <li class="nav-item start {{(Request::is($root_path.'/'.$authmenu[$i]->menu_name_alias.'/*') ? 'active open' : '')}}">
              <a href="javascript:;" class="nav-link nav-toggle">
                <i class="{{$authmenu[$i]->icon}}"></i>
                <span class="title">{{trans('general.'.$authmenu[$i]->menu_name_alias)}}</span>
                <span class="selected"></span>
                <span class="arrow {{Request::is($root_path.'/'.$authmenu[$i]->menu_name_alias.'/*') ? 'open' : ''}}"></span>
              </a>
              <ul class="sub-menu">
                @for ($x = 0;$x < count($msmenu[$i]); $x++)
                  <li class="nav-item start {{(Request::path() == $root_path.'/'.$authmenu[$i]->menu_name_alias.'/'.$msmenu[$i][$x]->menu_name_alias.'' || Request::is($root_path.'/'.$authmenu[$i]->menu_name_alias.'/'.$msmenu[$i][$x]->menu_name_alias.'/*') ? 'class=active open' : '')}}">
                    <a href="{{url($root_path.'/'.$authmenu[$i]->menu_name_alias.'/'.$msmenu[$i][$x]->menu_name_alias.'')}}" class="nav-link ">
                      <i class="fa fa-caret-right" aria-hidden="true"></i>
                      <span class="title">{{trans('general.'.$msmenu[$i][$x]->menu_name_alias)}}</span>
                      <span class="selected"></span>
                    </a>
                  </li>
                @endfor
              </ul>
            </li>
          @endif
        @endfor
      </ul>
    </div>
  </div>