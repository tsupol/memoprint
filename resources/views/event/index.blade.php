@extends('app')

@section('content')

    <div class="vd_head-section clearfix">
        <div class="vd_panel-header">
            <ul class="breadcrumb">
                <li><a href="index.php">Home</a> </li>
                <li class="active">Events</li>
            </ul>
        </div>
    </div>
    <div class="vd_title-section clearfix">
        <div class="vd_panel-header">
            <h1>Events</h1>
            <small class="subtitle">List of events</small> </div>
    </div>
    <div class="vd_content-section clearfix">

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel widget light-widget panel-bd-top">
                    <div class="panel-heading no-title"> </div>
                    <div class="panel-body">
                        <h3 class="mgtp--5">All Events</h3>
                        <div class="content-list content-blog-small">
                            <ul class="list-wrapper">
                                @foreach ($events as $event)
                                    <li>
                                        {{--<div class="menu-icon"> <img alt="example image" src="img/blog/01-square-200.jpg"> </div>--}}
                                        {{--<div class="menu-text">--}}
                                            {{--<h2 class="blog-title font-bold letter-xs"><a href="{{ url("event/{$event->id}/wall/") }}">{{ $event->name }}</a></h2>--}}
                                            {{--<div class="menu-info">--}}
                                                {{--<div class="menu-date font-xs">{{ $event->start_at->format('M d, Y H:i:s') }}--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="menu-tags  mgbt-xs-15"> Tags: <a href="#"><span class="label vd_bg-yellow">{{ $event->tag }}</span></a> </div>--}}
                                            {{--<p>{{ $event->description }}</p>--}}

                                            {{--<a class="btn vd_btn vd_bg-green btn-sm" href="{{ url('mvent/'.$event->id.'/edit') }}">Edit</a>--}}
                                            {{--<a class="btn vd_btn vd_bg-green btn-sm" href="{{ url('mvent/'.$event->id.'/choose') }}">Choose Image</a>--}}

                                        {{--</div> --}}
                                        <div class="">
                                            <h2 class="blog-title font-bold letter-xs"><a href="{{ url("event/{$event->id}/wall/") }}">{{ $event->name }}</a></h2>
                                            <div class="menu-info">
                                                <div class="menu-date font-xs">{{ $event->start_at->format('M d, Y H:i:s') }}
                                                </div>
                                            </div>
                                            <div class="menu-tags  mgbt-xs-15"> Tags: <a href="#"><span class="label vd_bg-yellow">{{ $event->tag }}</span></a> </div>
                                            <p>{{ $event->description }}</p>

                                            <a class="btn vd_btn vd_bg-green btn-sm" href="{{ url('mvent/'.$event->id.'/edit') }}">Edit</a>
                                            <a class="btn vd_btn vd_bg-green btn-sm" href="{{ url('event/'.$event->id.'/wall') }}">View Live</a>
                                            <a class="btn vd_btn vd_bg-green btn-sm" href="{{ url('mvent/'.$event->id.'/choose') }}">Show / Hide</a>

                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Panel Widget -->

            </div>
            <!-- col-md-4 -->

            {{--<div class="col-md-4">--}}
                {{--<div class="panel widget light-widget panel-bd-top vd_bdt-yellow">--}}
                    {{--<div class="panel-heading no-title"> </div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<h3 class="mgtp--5 mgbt-xs-20"> Medium Size for Sidebar</h3>--}}
                        {{--<div class="content-list content-image">--}}
                            {{--<ul class="list-wrapper no-bd-btm">--}}
                                {{--<li> <a href="#"> <div class="menu-icon"><img src="img/blog/01-square.jpg" alt="example image"></div> <div class="menu-text pd-5">--}}
                                            {{--<h5 class="mgbt-xs-0">Blog Sidebar Title</h5>--}}
                                            {{--<div class="menu-info"> <div class="menu-date font-xs">Posted: January 10th, 1987 </div>--}}
                                            {{--</div> </div> </a> </li>--}}
                                {{--<li> <a href="#"> <div class="menu-icon"><img src="img/blog/02-square.jpg" alt="example image"></div> <div class="menu-text pd-5">--}}
                                            {{--<h5 class="mgbt-xs-0">Cool Obligation</h5>--}}
                                            {{--<div class="menu-info"> <div class="menu-date font-xs">Posted: February 19th, 2017 </div>--}}
                                            {{--</div> </div> </a> </li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<!-- Panel Widget -->--}}

            {{--</div>--}}
            <!-- col-md-4 -->

        </div>
        <!--row -->
    </div>

@endsection

@section('page_script')
@endsection
