function imgError(image) {
    image.onerror = "";
    image.src = "http://103.245.167.79/memoprint/public/img/memoprint/notfound.jpg";
    return true;
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

jQuery(document).ready(function ($){

    var eventId = $(location).attr('href').split("/")[6];
    var host = "http://103.245.167.79/memoprint/public/event/";
    var imgUrl = "http://103.245.167.79/memoprint/public/memoprint/";
    var emptyImg = "http://103.245.167.79/memoprint/public/img/memoprint/empty.jpg";
    var imgs = [];
    var pics = [];
    var col = 6;
    var maxImg = 24;
    var isReady = false;
    var newPicCount = 0;

    var jqxhr = $.ajax( host+eventId )
        .done(function(msg) {
            var obj = JSON.parse(msg);
            var data = obj.data;
            for(var i=0; i<data.length; i++) {
                var found = false;
                for(var j=0; i<imgs.length; j++) {
                    if(imgs[j].id == data[i].id) {
                        found = true;
                        break;
                    }
                }
                if(!found) {
                    imgs.push(data[i]);
                    imgs[i].status = 'loading';
                    var $img = $('<img src="'+imgs[i].img_high+'" data-id="'+i+'">');
                    $('#imgloader').append($img);
                    $img
                        .load(function(){
                            var id = $(this).attr('data-id');
                            imgs[id].status = 'loaded';
                            console.log('loaded', id);
                            pics.push(imgs[id]);
                            newPicCount++;
                        })
                        .error(function(){
                            var id = $(this).attr('data-id');
                            imgs[id].status = 'error';
                        });
                }
            }


            isReady = true;
            //console.log(data);
        })
        .fail(function() {
            //alert( "error" );
        })
        .always(function() {
            //alert( "complete" );
        });

    for(i=0; i<maxImg; i++) {
        $('#wrapper').append(
            '<div class="card-wrap empty pause" data-id="'+-1+'"><div class="card"><div class="front face"><img src="'+emptyImg+'" onerror="imgError(this);"></div>'+
            '<div class="back face"><img src="'+emptyImg+'" onerror="imgError(this);"></div></div></div>');
    }

    window.setInterval(function () {
        if(newPicCount > 0) {
            if($("div.card-wrap.empty").length > 0) {
                var start = pics.length-newPicCount;
                var end = start + newPicCount;
                for(var i=start; i<end; i++) {
                    console.log(i);
                    var $target = $("div.card-wrap.empty").first();
                    $target
                        .removeClass('empty pause')
                        .attr('data-id', i)
                        .later(2000, function() {
                            $(this).addClass('pause');
                        })
                        .find('img').attr('src', pics[i].img_high);
                    newPicCount--;
                }
            } else {

            }
        }
        //console.log(pics.length);
        //if(pics.length <= maxImg) {
        //    var count = pics.length - $('div.card-wrap').length;
        //    if(count > 0) {
        //        for(i=0; i<count; i++) {
        //            $("div.card-wrap.empty").first().removeClass('empty').find(img).attr('src', pics[i].img_high);
        //        }
        //    }
        //}
        //if(pics.length > $('div.card-wrap').length && pics.length < maxImg) {
        //    var count = pics.length - $('div.card-wrap').length;
        //    for(i=0; i<maxImg && i<pics.length; i++) {
        //        $('#wrapper').append(
        //            '<div class="card-wrap" data-id="'+i+'"><div class="card"><div class="front face"><img src="'+pics[i].img_high+'" onerror="imgError(this);"></div>'+
        //            '<div class="back face"><img src="'+pics[i].img_high+'" onerror="imgError(this);"></div></div></div>');
        //    }
        //}
    }, 1000);

    //$(window).resize(function() {
    //    //var count = 6;
    //    //var w = $(window).width() / count;
    //    //$('div.card').width(w).height(w);
    //});

    //$(window).trigger('resize');

    // loop
    window.setInterval(function () {
        if(isReady) {
            cardId = 1;
            imgId = 10;
            //$('#wrapper>div:nth-child('+cardId+')').find('.back img').attr('src', imgs[imgId].img_high);
            //$('#wrapper>div:nth-child('+cardId+')').toggleClass('flip');
            //var max = $('.card-wrap').length-1;
            //var total = imgs.length;
            //var cardId = getRandomInt(0, max);
            //var imgId = -1;
            ////console.log('t', total);
            //if(total <= max) {
            //    console.log('dfdfdffdfdfdfd');
            //} else {
            //    for(var c=0; c<20; c++) {
            //        var id = getRandomInt(0, total-1);
            //        console.log('=',id);
            //        if($("#wrapper>div[data-id='"+id+"']").length > 0) {
            //            imgId = id;
            //            break;
            //        }
            //    }
            //}
            //console.log(imgId);
            //$('#wrapper>div:nth-child('+cardId+')').find('.back img').attr('src', imgs[imgId].img_high);
            //$('#wrapper>div:nth-child('+cardId+')').toggleClass('flip');
        }
    }, 1000); // repeat forever, polling every 3 seconds

    //$(document).on('error', 'img', function(e) { $(this).attr('src', imgUrl+'notfound.jpg'); });

});