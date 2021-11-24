<div class="footer-wrapper style-10">
    <footer class="type-1">
         <!-- BEGIN FOOTER -->
        <!-- BEGIN PRE-FOOTER -->
       <!-- <div class="page-prefooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                        <h2>About</h2>
                        <p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam dolore. </p>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs12 footer-block">
                        <h2>Subscribe Email</h2>
                        <div class="subscribe-form">
                            <form action="javascript:;">
                                <div class="input-group">
                                    <input type="text" placeholder="mail@email.com" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn" type="submit">Submit</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                        <h2>Follow Us On</h2>
                        <ul class="social-icons">
                            <li>
                                <a href="javascript:;" data-original-title="rss" class="rss"></a>
                            </li>
                            <li>
                                <a href="javascript:;" data-original-title="facebook" class="facebook"></a>
                            </li>
                            <li>
                                <a href="javascript:;" data-original-title="twitter" class="twitter"></a>
                            </li>
                            <li>
                                <a href="javascript:;" data-original-title="googleplus" class="googleplus"></a>
                            </li>
                            <li>
                                <a href="javascript:;" data-original-title="linkedin" class="linkedin"></a>
                            </li>
                            <li>
                                <a href="javascript:;" data-original-title="youtube" class="youtube"></a>
                            </li>
                            <li>
                                <a href="javascript:;" data-original-title="vimeo" class="vimeo"></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                        <h2>Contacts</h2>
                        <address class="margin-bottom-40"> Phone: 800 123 3456
                            <br> Email:
                            <a href="mailto:info@metronic.com">info@metronic.com</a>
                        </address>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- END PRE-FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <div class="page-footer">
            <div class="container">Copyright © 2018 &copy; <a href="/" title="Jetfi Indonesia" target="_blank">Jetfi Indonesia</a>  All Rights Reserved. Website created with <i class="fa fa-heart"></i> Elim Suhendra
            </div>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        <!-- END INNER FOOTER -->
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
    </footer>
</div>

<!-- BEGIN CORE PLUGINS -->

<script type="text/javascript" src="{{asset('components/plugins/jquery.min.js')}}"></script>
<script src="{{asset('components/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('components/plugins/moment.min.js')}}"></script>
<script src="{{asset('components/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<script src="{{asset('components/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}" type="text/javascript"></script>
<script src="{{asset('components/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<script src="{{asset('components/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{asset('components/plugins/uniform/jquery.uniform.min.js')}}" type="text/javascript"></script>
<script src="{{asset('components/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>

<script src="{{asset('components/plugins/tinymce/js/tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('components/plugins/select2/js/select2.full.min.js')}}"></script>

 <!-- END CORE PLUGINS -->
<script type="text/javascript" src="{{asset('components/front/js/idangerous.swiper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('components/plugins/swiper-slider/swiper.jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('components/front/js/global.js')}}"></script>
<script type="text/javascript" src="{{asset('components/front/js/jquery.mousewheel.js')}}"></script>
<script type="text/javascript" src="{{asset('components/front/js/jquery.jscrollpane.min.js')}}"></script>
<script src="{{asset('components/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js')}}"></script>

<script src="{{asset('components/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('components/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
<script src="{{asset('components/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>


<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script type="text/javascript" src="{{asset('components/front/js/metronic/global/scripts/app.min.js')}}"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script type="text/javascript" src="{{asset('components/front/js/metronic/layout3/scripts/layout.min.js')}}"></script>
<script type="text/javascript" src="{{asset('components/front/js/metronic/layout3/scripts/demo.min.js')}}"></script>
<script type="text/javascript" src="{{asset('components/front/js/metronic/global/scripts/quick-sidebar.min.js')}}"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<!-- place for scripts -->
@stack('scripts')
<script type="text/javascript" src="{{asset('components/front/js/kyubi-head.js')}}"></script>
<!-- <script src="{{asset('components/back/js/kyubi.js')}}"></script> -->
<!-- End place for scripts -->

<!-- place for custom_scripts -->
@stack('custom_scripts')
<!-- End place for custom_scripts -->