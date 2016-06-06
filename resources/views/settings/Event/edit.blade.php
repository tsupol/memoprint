@extends('app')

@section('content')
    <div class="vd_content-section clearfix">
        <div class="row" id="form-basic">
            <div class="col-md-12">
                <div class="panel widget">
                    <div class="panel-heading vd_bg-grey">
                        <h3 class="panel-title"> <span class="menu-icon"> <i class="fa fa-bar-chart-o"></i> </span> Brand Settings </h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ url('event/'.$event->id) }}" role="form" method="POST">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <!-- Items 555 -->

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" placeholder="Disabled input here..." value="{{ $event->name }}" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tag</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" placeholder="Disabled input here..." value="{{ $event->tag }}" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tag 2</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" placeholder="Disabled input here..." value="{{ $event->tag2 }}" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Fan Id</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" placeholder="Disabled input here..." value="{{ $event->fan_id }}" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Mode</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" disabled="" placeholder="Disabled input here..." value="{{ $event->mode }}" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tag Mode</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" disabled="" placeholder="Disabled input here..." value="{{ $event->tag_mode }}" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Time</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" disabled="" placeholder="Disabled input here..." value="{{ $fromTo }}" /></div>
                            </div>

                            <div class="form-group form-actions">
                                <div class="col-sm-4"> </div>
                                <div class="col-sm-7">
                                    <button class="btn vd_btn vd_bg-green vd_white" type="submit"><i class="icon-ok"></i> Save</button>
                                    <button class="btn vd_btn" type="button">Reset</button>
                                    <button class="btn vd_btn json_btn" type="button">JSON</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- Panel Widget -->
            </div>
            <!-- col-md-12 -->
        </div>
        <!-- row -->
    </div>
@endsection

@section('page_script')
    @include("igm/scripts/script-dashboard")
@endsection