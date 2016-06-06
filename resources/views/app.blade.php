<!DOCTYPE html>
<!--[if IE 8]>          <html class="ie ie8"> <![endif]-->
<!--[if IE 9]>          <html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->  <html><!--<![endif]-->
@include('igm/headers/'.$header)

<div class="content">
    <div class="container" id="igm-cont">

        @if($navbar_left_config != 0)
            @include('igm/navbars/'.$navbar_left)
        @endif

        <div class="vd_content-wrapper">
            <div class="vd_container">
                <div class="vd_content clearfix">

                    @yield('content')

                </div>
            </div>
        </div>
    </div>
    {{--sticky footer--}}
    <div id="push"></div>
</div>

@include("igm/footers/".$footer)

<!-- Specific Page Scripts Put Here -->
@yield('page_script')

@include("igm/footers/after-scripts")
<!-- Specific Page Scripts END -->

@include("igm/footers/closing")