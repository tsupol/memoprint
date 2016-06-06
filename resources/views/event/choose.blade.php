@extends('app')

@section('content')

    <div ng-app="Mvent">
        <div ng-view></div>
    </div>

@endsection

@section('page_script')
    <script>
        window.event_id = '{{ $event_id }}';
    </script>
    <script type="text/javascript" src="{{ asset('angular/mvent.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ng-infinite-scroll.min.js') }}"></script>
@endsection