<style type="text/css">
    .page-header .page-header-top{
        height: 100%;
    }
    .page-header .page-header-menu{
        background: white;
    }
    .page-header .page-header-top .page-logo {
        float: left;
        display: block;
        width: 255px;
        height: 51px;
    }

    .page-header .page-header-top .page-logo {
        float: left;
        display: block;
        width: 255px;
        height: 51px;
    }

    .page-header .page-header-menu .hor-menu {
        float: right;
    }

    .page-header .page-header-top #page_logo-2{
        display: none;
    }

    @media screen and (max-width: 1023px){
        .page-header .page-header-menu .page-logo {
            display: none;
        }

        .page-header .page-header-top #page_logo-2{
            display: block;
        }


        .page-header .page-header-menu .hor-menu{
            float: none;
        }
    }
</style>
<!-- BEGIN HEADER -->
        <div class="page-header">
            <div class="top-container" style="
            color: #ffd900;">
                <div class="container">
                    <div class="top-text" id="changed-text">WELCOME TO JETFI INDONESIA'S WEBSITE</div>
                    <div class="top_menu-2">
                        <div class="region"><p class="top-jetfi" style="margin: 10px 0;">Jetfi</p></div>
                        <div class="dropdown">
                            <!-- <div class="region-2">
                                <img src="https://jetfi.co.id/wp-content/uploads/2017/10/Indonesia-Flag.jpg"><span>Indonesia</span>
                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                            </div>
                            <ul class="nav navbar-nav">
                                <li class="menu-dropdown classic-menu-dropdown active">
                                    <a href="http://www.jetfi-tech.com.tw/"><img src="https://jetfi.co.id/wp-content/uploads/2017/10/Taiwan.jpg">Taiwan</a>
                                </li>
                            </ul> -->
          <!--                   <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="" data-close-others="true" aria-expanded="false" style="color: black;">
                                <img src="">
                                <span>Indonesia</span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                @foreach($lang as $lg)
                                    <li class="menu-dropdown classic-menu-dropdown active">
                                        <a href="http://www.jetfi-tech.com.tw/">
                                            <img src="{{$image_path.$lg->image}}"> {{$lg->language_name}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul> -->
                           <!--  <span style="
                                display: inline-block;
                                color: black;
                                float: right;
                            ">{{$curr_lang}}</span> -->
                            <form action="{{ Request::segment(1) != NULL ? Request::fullUrl() : ' ' }}" method="post" id="form_lang" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <select class="language_box" onchange="this.form.submit()" id="language_box" name="langs_box">
                                @foreach($lang as $lg)
                                  <option value="{{ $lg->id }}" {{($curr_lang == $lg->id ? 'selected' : '')}}>{{ ucfirst($lg->language_name)}}</option>
                                @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BEGIN HEADER TOP -->
            <div class="page-header-top">
                <div class="container">
                    <div class="page-logo" id="page_logo-2" style="">
                        <a href="{{url('/')}}">
                            <img src="{{asset('components/back/images/admin').'/'.$web_logo}}" alt="logo" class="img-responsive"style="display: unset;height: 100%;">
                        </a>
                    </div>
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler" style="margin: 13px 3px 0 13px;"></a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                </div>

                <!-- BEGIN HEADER MENU -->
            <div class="page-header-menu">
                <div class="container">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo" style="">
                        <a href="{{url('/')}}">
                            <img src="{{asset('components/back/images/admin').'/'.$web_logo}}" alt="logo" class="img-responsive"style="display: unset;height: 100%;">
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN HEADER SEARCH BOX -->
                    <!-- <form class="search-form" action="page_general_search.html" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" name="query">
                            <span class="input-group-btn">
                                <a href="javascript:;" class="btn submit">
                                    <i class="icon-magnifier"></i>
                                </a>
                            </span>
                        </div>
                    </form> -->
                    <!-- END HEADER SEARCH BOX -->
                    <!-- BEGIN MEGA MENU -->
                    <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                    <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                    <div class="hor-menu  ">
                        <ul class="nav navbar-nav">
                            @foreach($menus as $key => $m)
                                <li class="menu-dropdown classic-menu-dropdown {{$key == 0 ? 'active' : ''}}" id="menu-{{$m->id}}">
                                    <a href="{{url($m->slug)}}"> {{$m->menu_name}}
                                        <!-- <span class="arrow"></span> -->
                                    </a>
                                    <!-- <ul class="dropdown-menu pull-left">
                                        <li class=" ">
                                            <a href="index.html" class="nav-link  ">
                                                <i class="icon-bar-chart"></i> Default Dashboard
                                                <span class="badge badge-success">1</span>
                                            </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dashboard_2.html" class="nav-link  ">
                                                <i class="icon-bulb"></i> Dashboard 2 </a>
                                        </li>
                                        <li class=" ">
                                            <a href="dashboard_3.html" class="nav-link  ">
                                                <i class="icon-graph"></i> Dashboard 3
                                                <span class="badge badge-danger">3</span>
                                            </a>
                                        </li>
                                    </ul> -->
                                </li>
                            @endforeach
                            <!-- <li class="menu-dropdown classic-menu-dropdown" id="about">
                                <a href="{{url('/')}}"> About -->
                                    <!-- <span class="arrow"></span> -->
                              <!--   </a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown" id="contactt">
                                <a href="{{url('/')}}"> Contact -->
                                    <!-- <span class="arrow"></span> -->
                                <!-- </a>
                            </li> -->
                        </ul>
                    </div>
                    <!-- END MEGA MENU -->
                </div>
            </div>
            </div>
            <!-- END HEADER TOP -->
            
            <!-- END HEADER MENU -->

            <div class="container">
                 <!-- BEGIN LOGO -->
                   <!--  <div class="page-logo" style="text-align: center;">
                        <a href="{{url('/')}}">
                            <img src="{{asset('components/back/images/admin').'/'.$web_logo}}" alt="logo" class="img-responsive"style="display: unset;">
                        </a>
                    </div> -->
                    <!-- END LOGO -->
            </div>
        </div>

