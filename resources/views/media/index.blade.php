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
            @foreach ($media as $m)
                <div class="col-md-2">
                    <img src="{{$m->media->img_low}}" />
                    <p>{{$m->media_id}}</p>

                    @if($m->is_use)
                        <a href="memoprint/public/media/{{$m->id}}">hide</a>
                    @else
                        <button>hide</button>
                    @endif
                </div>
            @endforeach

            </div>
            <!-- col-md-4 -->
        </div>
        <!--row -->
    </div>

@endsection
@section('page_script')
@endsection
