jQuery(document).ready(function ($){

    // set active menu
    if($('body').attr('data-active') == 'viewer') {
        $('#nav-viewer').show().siblings('a').addClass('open');
    } else {
        $('div.vd_menu a[href="' + location.href + '"]').parent().addClass('active').parent().parent().show().siblings('a').addClass('open');
    }

    // active menu for AJAX
    $('div.vd_menu .child-menu li').click(function() {
        $(this).addClass('active').siblings().removeClass('active');
    });

    // angular

    // navbar fix
    $(window).resize(function() {
        $('div.vd_body>.content').css({'min-height': ($(window).height() - 63) + 'px'});
        $('div#igm-cont').css({'min-height': ($(window).height() - 106) + 'px'});
    });
    $(window).trigger('resize');

    $('#search-box').submit(function(e) {
        e.preventDefault();
        window.location.href = "http://103.245.167.79/memoprint/public/viewer#/search/"+$('#search').val();
    });

});