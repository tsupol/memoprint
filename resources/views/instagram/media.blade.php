@extends('app')

@section('content')

    <div class="vd_head-section clearfix">
        <div class="vd_panel-header">
            <ul class="breadcrumb">
                <li><a href="index.php">Home</a> </li>
                <li><a href="gallery-page.php">Gallery</a> </li>
                <li class="active">Gallery Masonry</li>
            </ul>
            @include("templates/widgets/panel-menu-head-section")
        </div>
    </div>
    <div class="vd_title-section clearfix">
        <div class="vd_panel-header">
            <h1>Gallery Masonry</h1>
            <small class="subtitle">Gallery with isotope plugins masonry style</small> </div>
    </div>
    <div class="vd_content-section clearfix">
        <div class="insta-wrapper row">
            @foreach($insta->data as $media)
            <div class="col-sm-2">
                <div class="elem">
                    <div class="elem-header">
                        <img src="{{ $media->user->profile_picture }}">
                        <p>{{ $media->user->username }}</p>
                    </div>
                    <a class="elem-img" href="{{ $media->link }}">
                        <img alt="{{ $media->type }}" src="{{ $media->images->thumbnail->url }}">
                    </a>
                    <div class="elem-footer">
                        <i class="fa fa-fw fa-heart"></i>{{ $media->likes->count }}
                        <i class="fa fa-fw fa-comment"></i>{{ $media->comments->count }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="clearfix"></div>
    </div>

@endsection

@section('page_script')
    @include("templates/scripts/gallery-masonry")
@endsection
