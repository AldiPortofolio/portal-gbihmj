<div class="page-header navbar navbar-fixed-top">
  <div class="page-header-inner ">
    <div class="page-logo">
        <a href="<?php echo e(url($root_path)); ?>">
          <img src="<?php echo e(asset('components/back/images/admin')); ?>/<?php echo e($web_logo); ?>" alt="logo" class="logo-default" />
        </a>
        <div class="menu-toggler sidebar-toggler"></div>
    </div>
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
    <div class="page-top">
      <div class="top-menu">
        <ul class="nav navbar-nav pull-right">
          <li class="dropdown dropdown-user dropdown-dark">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
              <span class="username"> <?php echo e(ucwords(auth()->guard($guard)->user()->username)); ?> </span>
              <img alt="" class="img-circle" src="<?php echo e(asset('components/admin/image/user')); ?>/<?php echo e(auth()->guard($guard)->user()->images ? auth()->guard($guard)->user()->images : '../../../admin/image/default.jpg'); ?>" /> 
            </a> 
            <ul class="dropdown-menu dropdown-menu-default">
              <li>
                <a href="<?php echo e(url($root_path.'/profile')); ?>">
                <i class="fa fa-user"></i><?php echo e(trans('general.profile')); ?></a>
              </li>
              <li>
                <a href="<?php echo e(url($root_path.'/logout')); ?>">
                <i class="fa fa-key"></i> <?php echo e(trans('general.sign_out')); ?> </a>
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
                  <a class="view-all" href="<?php echo e(url($root_path.'/notification/manage-notification')); ?>">view all</a>
              </li>
             
              <!-- <li>
                <a href="<?php echo e(url($root_path.'/logout')); ?>">
                <i class="fa fa-key"></i> <?php echo e(trans('general.sign_out')); ?> </a>
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
        <?php for($i = 0;$i < count($authmenu); $i++): ?>
          <?php if($authmenu[$i]->heading): ?>
            <li class="heading">
              <h3 class="uppercase"><?php echo e(trans('general.'.strtolower($authmenu[$i]->heading))); ?></h3>
            </li>
          <?php endif; ?>
          <?php if(count($msmenu[$i]) == 0): ?>
            <li class="nav-item start <?php echo e($authmenu[$i]->menu_name_alias == '/' ? (Request::path() == $root_path ? 'active' : '') : Request::path() == $root_path.'/'.$authmenu[$i]->menu_name_alias || Request::is($root_path.'/'.$authmenu[$i]->menu_name_alias.'/*') ? 'active' : ''); ?>">
              <a href="<?php echo e(url($root_path.'/'.$authmenu[$i]->menu_name_alias.'')); ?>" class="nav-link ">
                <i class="<?php echo e($authmenu[$i]->icon); ?>"></i>
                <span class="title"><?php echo e(trans('general.'.$authmenu[$i]->menu_name_alias)); ?></span>
                <span class="selected"></span>
              </a>
            </li>
          <?php else: ?>
            <li class="nav-item start <?php echo e((Request::is($root_path.'/'.$authmenu[$i]->menu_name_alias.'/*') ? 'active open' : '')); ?>">
              <a href="javascript:;" class="nav-link nav-toggle">
                <i class="<?php echo e($authmenu[$i]->icon); ?>"></i>
                <span class="title"><?php echo e(trans('general.'.$authmenu[$i]->menu_name_alias)); ?></span>
                <span class="selected"></span>
                <span class="arrow <?php echo e(Request::is($root_path.'/'.$authmenu[$i]->menu_name_alias.'/*') ? 'open' : ''); ?>"></span>
              </a>
              <ul class="sub-menu">
                <?php for($x = 0;$x < count($msmenu[$i]); $x++): ?>
                  <li class="nav-item start <?php echo e((Request::path() == $root_path.'/'.$authmenu[$i]->menu_name_alias.'/'.$msmenu[$i][$x]->menu_name_alias.'' || Request::is($root_path.'/'.$authmenu[$i]->menu_name_alias.'/'.$msmenu[$i][$x]->menu_name_alias.'/*') ? 'class=active open' : '')); ?>">
                    <a href="<?php echo e(url($root_path.'/'.$authmenu[$i]->menu_name_alias.'/'.$msmenu[$i][$x]->menu_name_alias.'')); ?>" class="nav-link ">
                      <i class="fa fa-caret-right" aria-hidden="true"></i>
                      <span class="title"><?php echo e(trans('general.'.$msmenu[$i][$x]->menu_name_alias)); ?></span>
                      <span class="selected"></span>
                    </a>
                  </li>
                <?php endfor; ?>
              </ul>
            </li>
          <?php endif; ?>
        <?php endfor; ?>
      </ul>
    </div>
  </div>