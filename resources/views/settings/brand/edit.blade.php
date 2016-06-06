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
                        <form class="form-horizontal" action="{{ url('brand/'.$brand->id) }}" role="form" method="POST">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <!-- Items -->

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Username</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" disabled="" placeholder="Disabled input here..." value="{{ $username }}" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Brand Name</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" disabled="" placeholder="Disabled input here..." value="{{ $brandname }}" ></div>
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<label class="col-sm-4 control-label">Input</label>--}}
                                {{--<div class="col-sm-7 controls">--}}
                                    {{--<input type="text" placeholder="small">--}}
                                    {{--<span class="help-inline">Some hint here</span> </div>--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Settings</label>
                                <div class="col-sm-7 controls">
                                    <textarea id="settings" name="settings" rows="10" class="width-50">{{ $brand->settings }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tags</label>
                                <div class="col-sm-7 controls">
                                    <input name="tags" type="text" class="tags" data-rel="tags-input" value="{{ $brand->tags }}" />
                                </div>
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