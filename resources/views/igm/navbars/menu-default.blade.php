 <ul>
     {{--<li>--}}
    	{{--<a href="{{ url('dashboard') }}">--}}
        	{{--<span class="menu-icon"><i class="fa fa-dashboard"></i></span>--}}
            {{--<span class="menu-text">Dashboard</span>--}}
       	{{--</a>--}}
     {{--</li>--}}
     <li>
    	<a href="{{ url('mvent/create') }}">
        	<span class="menu-icon"><i class="fa fa-plus"></i></span>
            <span class="menu-text">Add</span>
       	</a>
     </li>
     <li>
    	<a href="{{ url('mvent') }}">
        	<span class="menu-icon"><i class="fa fa-picture-o"></i></span>
            <span class="menu-text">Events</span>
       	</a>
     </li>
     {{--<li>--}}
         {{--<a href="javascript:void(0);" data-action="click-trigger">--}}
             {{--<span class="menu-icon"><i class="fa fa-picture-o"></i></span>--}}
             {{--<span class="menu-text">Events</span>--}}
             {{--<span class="menu-badge"><span class="badge vd_bg-black-30"><i class="fa fa-angle-down"></i></span></span>--}}
         {{--</a>--}}
         {{--<div id="nav-viewer" class="child-menu"  data-action="click-target">--}}
             {{--<ul>--}}
                 {{--<li>--}}
                     {{--<a href="{{ url('mvent/create') }}">--}}
                         {{--<span class="menu-text">Add</span>--}}
                     {{--</a>--}}
                 {{--</li>--}}
                 {{--<li>--}}
                     {{--<a href="{{ url('mvent') }}">--}}
                         {{--<span class="menu-text">Events</span>--}}
                     {{--</a>--}}
                 {{--</li>--}}
             {{--</ul>--}}
         {{--</div>--}}
     {{--</li>--}}
     {{--<li>--}}
        {{--<a href="javascript:void(0);" data-action="click-trigger">--}}
            {{--<span class="menu-icon"><i class="fa fa-signal"></i></span> --}}
            {{--<span class="menu-text">Analytics</span>  --}}
            {{--<span class="menu-badge"><span class="badge vd_bg-black-30"><i class="fa fa-angle-down"></i></span></span>--}}
        {{--</a>--}}
        {{--<div class="child-menu"  data-action="click-target">--}}
            {{--<ul>--}}
                {{--<li>--}}
                    {{--<a href="{{ url('analytics/presenters') }}">--}}
                        {{--<span class="menu-text">Presenters</span>  --}}
                    {{--</a>--}}
                {{--</li>                                                                                              --}}
            {{--</ul>   --}}
        {{--</div>--}}
    {{--</li> --}}
    <li>
        <a href="javascript:void(0);" data-action="click-trigger">
            <span class="menu-icon"><i class="fa fa-picture-o"></i></span>
            <span class="menu-text">Viewer</span>
            <span class="menu-badge"><span class="badge vd_bg-black-30"><i class="fa fa-angle-down"></i></span></span>
        </a>
        <div id="nav-viewer" class="child-menu"  data-action="click-target">
            <ul>
                <li>
                    <a href="{{ url('viewer#/feed') }}">
                        <span class="menu-text">Feed</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('viewer#/media') }}">
                        <span class="menu-text">My media</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('viewer#/likes') }}">
                        <span class="menu-text">My likes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('viewer#/followers') }}">
                        <span class="menu-text">My followers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('viewer#/followings') }}">
                        <span class="menu-text">My following</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('viewer#/populars') }}">
                        <span class="menu-text">populars</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
</ul>
<!-- Head menu search form ends --> 