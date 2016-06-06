<!-- Flot Chart  -->
<script type="text/javascript" src="{{ asset('/plugins/flot/jquery.flot.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/flot/jquery.flot.resize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/flot/jquery.flot.pie.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/flot/jquery.flot.categories.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/flot/jquery.flot.time.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/flot/jquery.flot.animator.min.js') }}"></script>

<!-- Vector Map -->
<script type="text/javascript" src="{{ asset('/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>

<!-- Calendar -->
<script type="text/javascript" src="{{ asset('/plugins/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/jquery-ui/jquery-ui.custom.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/fullcalendar/fullcalendar.min.js') }}"></script>

<!-- Intro JS (Tour) -->
<script type="text/javascript" src="{{ asset('/plugins/introjs/js/intro.min.js') }}"></script>

<!-- Sky Icons -->
<script type="text/javascript" src="{{ asset('/plugins/skycons/skycons.js') }}"></script>

<!-- Flot Chart  -->
<script type="text/javascript" src="{{ asset('/js/highcharts.js') }}"></script>


<script type="text/javascript">
$(window).load(function () {
	$('ul.nav-tabs>li>a').click(function(e) {
        e.preventDefault();
    });
    $('.json_btn').click(function () {
        console.log($.parseJSON($("#settings").val()));
    });
});
</script>