<!DOCTYPE html>
<!--[if IE 8]>          <html class="ie ie8"> <![endif]-->
<!--[if IE 9]>          <html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->  <html><!--<![endif]-->
@include('igm/headers/'.$header)

<div class="content">
    <div class="container" id="igm-cont">

        <div class="vd_content-wrapper">
            <div class="vd_container">
                <div class="vd_content clearfix">

                    @yield('content')

                </div>
            </div>
        </div>

    </div>
</div>

@include('igm/footers/'.$footer)

    @yield('page_script')

@include("igm/footers/closing")






