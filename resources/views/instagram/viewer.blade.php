@extends('app')

@section('content')

    <div ng-app="Viewer">
        <div ng-view></div>
    </div>

@endsection

@section('page_script')
    <script type="text/javascript" src="{{ asset('angular/viewer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ng-infinite-scroll.min.js') }}"></script>
@endsection