@extends('empty')
@section('head')
    <link href='http://fonts.googleapis.com/css?family=Quicksand:300,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('css/wall.css') }}">
@endsection
@section('content')
    <div id="imgloader"></div>
    <div id="profileloader"></div>
    <div id="wrapper">
    </div>
    {{--<div id="footer"><div>#saitopwedding</div></div>--}}
    <div id="footer"><div>Thank you for coming</div></div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/later/src/jquery.later.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/wall.js') }}"></script>
@endsection