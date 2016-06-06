@extends('blank')

@section('content')

<div class="vd_content-section clearfix">
    <div class="vd_register-page">
        <div class="heading clearfix">
            <div class="logo">
                <h2 class="mgbt-xs-5"><img src="{{ asset('img/logo.png') }}" alt="logo"></h2>
            </div>
            <h4 class="text-center font-semibold vd_grey">USER REGISTRATION</h4>
        </div>
        <div class="panel widget">
            <div class="panel-body">
                <!--                  <div id="register-success" class="alert alert-success" style="display:none;"><i class="fa fa-exclamation-circle fa-fw"></i> Registration confirmation has been sent to your email </div>
                                  <div id="register-passerror" class="alert alert-danger" style="display:none;"><i class="fa fa-exclamation-circle fa-fw"></i> Your password and Confirm password are not same </div>-->
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="alert alert-danger vd_hidden">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="icon-cross"></i></button>
                        <span class="vd_alert-icon"><i class="fa fa-exclamation-circle vd_red"></i></span><strong>Oh snap!</strong> Change a few things up and try submitting again. </div>
                    <div class="alert alert-warning vd_hidden">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="icon-cross"></i></button>
                        <span class="vd_alert-icon"><i class="fa fa-exclamation-circle vd_red"></i></span>Your password and Confirm password are not same </div>
                    <div class="alert alert-success vd_hidden">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="icon-cross"></i></button>
                        <span class="vd_alert-icon"><i class="fa fa-check-circle vd_green"></i></span>Registration confirmation has been sent to your email. </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="label-wrapper">
                                <label class="control-label">Name <span class="vd_red">*</span></label>
                            </div>
                            <div class="vd_input-wrapper" id="first-name-input-wrapper"> <span class="menu-icon"> <i class="fa fa-user"></i> </span>
                                <input name="name" id="name" type="text" placeholder="John" class="required" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="label-wrapper">
                                <label class="control-label">Email <span class="vd_red">*</span></label>
                            </div>
                            <div class="vd_input-wrapper" id="email-input-wrapper"> <span class="menu-icon"> <i class="fa fa-envelope"></i> </span>
                                <input type="email" placeholder="Email" class="required" required  name="email" id="email">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="label-wrapper">
                                <label class="control-label">Password <span class="vd_red">*</span></label>
                            </div>
                            <div class="vd_input-wrapper" id="password-input-wrapper"> <span class="menu-icon"> <i class="fa fa-lock"></i> </span>
                                <input type="password" placeholder="Password" class="required" required  name="password" id="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="label-wrapper">
                                <label class="control-label">Confirm Password <span class="vd_red">*</span></label>
                            </div>
                            <div class="vd_input-wrapper" id="confirm-password-input-wrapper"> <span class="menu-icon"> <i class="fa fa-lock"></i> </span>
                                <input type="password" placeholder="Password" class="required" required  name="password_confirmation" id="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div id="vd_login-error" class="alert alert-danger hidden"><i class="fa fa-exclamation-circle fa-fw"></i> Please fill the necessary field </div>
                    <div class="form-group">
                        <div class="col-md-12 mgbt-xs-10 mgtp-20">
                            <div class="vd_checkbox">
                                <input type="checkbox" id="checkbox-2" value="1" required name="checkbox-2">
                                <label for="checkbox-2"> I agree with <a href="{{ url('terms') }}">terms of service</a></label>
                            </div>
                        </div>
                        <div class="col-md-12 text-center mgbt-xs-5">
                            <button class="btn vd_bg-green vd_white width-100" type="submit" id="submit-register" name="submit-register">Register</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- Panel Widget -->
        <div class="register-panel text-center font-semibold"> Already Have an Account? <br/>
            <a href="{{ url('auth/login') }}">SIGN IN<span class="menu-icon"><i class="fa fa-angle-double-right fa-fw"></i></span></a> </div>
    </div>
    <!-- vd_login-page -->

</div>
<!-- .vd_content-section -->

@endsection

@section('page_script')

    <script type="text/javascript" src="{{ asset('page_scripts/register.js') }}"></script>

@endsection
