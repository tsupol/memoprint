@extends('app')

@section('content')
    <div class="vd_content-section clearfix">
        <div class="row" id="form-basic">
            <div class="col-md-12">
                <div class="panel widget">
                    <div class="panel-heading vd_bg-grey">
                        <h3 class="panel-title"> <span class="menu-icon"> <i class="fa fa-bar-chart-o"></i> </span> Create New Event</h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ url('mvent') }}" role="form" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <!-- Items -->

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Name <span class="vd_red">*</span></label>
                                <div class="col-sm-7 controls">
                                    <input type="text" name="name" placeholder="Name" value="" class="required" required/></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Description</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" name="description" placeholder="Desc" value="" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tag <span class="vd_red">*</span></label>
                                <div class="col-sm-7 controls">
                                    <input type="text" name="tag" placeholder="Tag" value="" class="required" required/></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tag2</label>
                                <div class="col-sm-7 controls">
                                    <input type="text" name="tag2" placeholder="Tag2" value="" /></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Active</label>
                                <div class="col-sm-7 controls">
                                    <input data-size="mini" type="checkbox" name="is_active" data-rel="switch" checked>
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Start - Finish</label>
                                <div class="col-sm-3 controls">
                                    <input type="text" placeholder="Date" name="start-finish" id="datepicker-datetime">
                                </div>
                            </div>

                            <div class="form-group form-actions">
                                <div class="col-sm-4"> </div>
                                <div class="col-sm-7">
                                    <button class="btn vd_btn vd_bg-green vd_white" type="submit"><i class="icon-ok"></i> Save</button>
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
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript">
        $(window).load(function () {
            $('#datepicker-datetime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' });
        });
    </script>
@endsection