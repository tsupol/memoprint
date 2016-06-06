@extends('app')

@section('content')
    <div class="vd_head-section clearfix">
        <div class="vd_panel-header">
            <ul class="breadcrumb">
                <li><a href="index.php">Home</a> </li>
                <li class="active">Default Dashboard</li>
            </ul>
        </div>
    </div>
    <!-- vd_head-section -->

    <div class="vd_title-section clearfix">
        <div class="vd_panel-header">
            <h1>Dashboard</h1>
            <small class="subtitle">Default dashboard for multipurpose demonstration</small>
            @include("igm/_widgets/panel-menu-title-section-index")
        </div>
        <!-- vd_panel-header -->
    </div>
    <!-- vd_title-section -->

    <div id="dash-content" ng-app="Dashboard">
        <div ng-view></div>
    </div>

@endsection

@section('page_script')
    <script type="text/javascript" src="{{ asset('angular/dashboard.js') }}"></script>
    <script type="text/javascript" src="http://103.245.167.79:3003/socket.io/socket.io.js"></script>
    @include("igm/scripts/script-dashboard")
@endsection