function imgError(image) {
    image.onerror = "";
    image.src = "http://103.245.167.79/memoprint/public/img/memoprint/anonymousUser.jpg";
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
    var maxImg = col*3;
    var isReady = false;
    var newPicCount = 0;
    var newPics = [];
    var lc = 1;
    var init = false;

    var clusters = [];
    clusters[0] = [0, 1, 6, 7, 2];
    clusters[1] = [10, 11, 16, 17, 15];

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
                    var $img = $('<img src="'+imgs[i].img_high+'" data-id="'+i+'" data-time="0">');
                    $('#imgloader').append($img);
                    $img
                        .load(function(){
                            var id = $(this).attr('data-id');
                            $(this).addClass('loaded');
                            imgs[id].status = 'loaded';
                            //console.log('loaded', id);
                            pics.push(imgs[id]);
                            pics[pics.length-1].age = 0;
                            newPicCount++;
                        })
                        .error(function(){
                            var id = $(this).attr('data-id');
                            $(this).addClass('error');
                            imgs[id].status = 'error';
                        });
                    var $pimg = $('<img src="'+imgs[i].profile_picture+'" data-id="'+i+'" data-time="0">');
                    $('#profileloader').append($pimg);
                    $pimg
                        .load(function(){
                            var id = $(this).attr('data-id');
                            $(this).addClass('loaded');
                        })
                        .error(function(){
                            var id = $(this).attr('data-id');
                            $(this).addClass('error');
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
            '<div class="card-wrap empty finish" data-id="'+-1+'"><div class="card">'+
            '<div class="front face"></div>'+
            '<div class="back face"></div>'+
            '</div></div>');
    }

    function getOldestPic() {
        // oldest first
        var h = -1;
        var hIdx = -1;
        // not show
        for (var i = 0; i < pics.length; i++) {
            if (pics[i].show) continue;
            if (pics[i].age > h) {
                h = pics[i].age;
                hIdx = i;
            }
        }
        if(hIdx == -1) {
            for (var i = 0; i < pics.length; i++) {
                if (pics[i].age > h) {
                    h = pics[i].age;
                    hIdx = i;
                }
            }
        }
        pics[hIdx].age = 0;
        return hIdx;
    }

    function flipCard(targetId, hIdx, delay, isProfile) {
        delay = typeof delay !== 'undefined' ? delay : 0;
        setTimeout(function(){
            var $target = $cards.eq(targetId);
            var face = 'front';
            if ($target.hasClass('back')) {
                $target.removeClass('back').addClass('front');
            } else {
                $target.removeClass('front').addClass('back');
                face = 'back';
            }
            if(isProfile) {
                $target.find('.' + face).css({'background-image': ''});
                $target.find('.'+face).addClass('showpro')
                    .html(
                    '<img class="profile" src="'+pics[hIdx].profile_picture+
                    '" onerror="imgError(this);" /><div class="username">'+pics[hIdx].username+'</div>');
            } else {
                $target.find('.' + face).css('background-image', 'url(' + pics[hIdx].img_high + ')');
                $target.removeClass('profile');
            }
            pics[hIdx].show = true;
            pics[parseInt($target.attr('data-id'))].show = false;
            $target
                .removeClass('finish')
                .attr('data-id', hIdx)
                .later(3000, function () {
                    $(this).addClass('finish ' + face);
                });
        },delay);
    }

    window.setInterval(function () {

        // new image
        if(!init) {
            //console.log('pic',pics.length);
            if(pics.length >= maxImg-4) {
                // cluster
                $cards = $(".card-wrap");
                for (var ci = 0; ci < clusters.length; ci++) {
                    var hIdx = getOldestPic();
                    console.log('hIdx',hIdx );
                    pics[hIdx].show = true;
                    for (var i = 0; i < clusters[ci].length; i++) {
                        var $target = $cards.eq(clusters[ci][i]);
                        $target.attr('data-id',hIdx);
                        if (i == 4) {
                            flipCard(clusters[ci][i], hIdx, 2 * 200, true);
                            $target.removeClass('empty').addClass('cluster c' + ci);
                        } else {
                            var face = 'back';
                            flipCard(clusters[ci][i], hIdx, i * 200);
                            $target.removeClass('empty').addClass('cluster c' + ci).find('.face.' + face).addClass('big b' + i);
                        }
                    }
                }

                //other
                setTimeout(function() {
                    $cards = $(".card-wrap.finish:not('.cluster')");
                    for (var i = 0; i < $cards.length; i++) {
                        var hIdx = getOldestPic();
                        pics[hIdx].show = true;
                        flipCard(i, hIdx, i*100);
                    }
                }, 4000);
                init = true;
            }
        } else if(newPicCount > 0) {

            if($(".card-wrap.empty").length > 0) {
                //var start = pics.length-newPicCount;
                //var end = start + newPicCount;
                //for(var i=start; i<end; i++) {
                //    //console.log('show', i);
                //    if($("div.card-wrap.empty").length < 1) {
                //        newPicCount = 0;
                //        pics[i].age = 0;
                //        pics[i].show = false;
                //        continue;
                //    }
                //    var $target = $("div.card-wrap.empty").first();
                //    var face = 'back';
                //    $target.find('.' + face).css('background-image', 'url(' + pics[i].img_high + ')');
                //    pics[i].age = 0;
                //    pics[i].show = true;
                //    $target
                //        .addClass('back')
                //        .removeClass('empty finish')
                //        .attr('data-id', i)
                //        .later(3000, function() {
                //            $(this).addClass('finish back');
                //        });
                //    newPicCount--;
                //}
            } else {

            }
        } else {
            if(lc%5 == 0) {
                var ci = (lc%10 == 0) ? 1 : 0;
                $cards = $(".card-wrap");
                if(newPics.length == 0) {
                    var hIdx = getOldestPic();
                    for (var i = 0; i < clusters[ci].length; i++) {
                        if(i == 4) {
                            var $target = $cards.eq(clusters[ci][i]);
                            flipCard(clusters[ci][i], hIdx, 2*200, true);
                            $target.addClass('cluster c'+ci);
                        } else {
                            var $target = $cards.eq(clusters[ci][i]);
                            var face = 'front';
                            if ($target.hasClass('front')) {
                                face = 'back';
                            }
                            flipCard(clusters[ci][i], hIdx, i*200);
                            $target.addClass('cluster c'+ci).find('.face.'+face).addClass('big b'+i);
                        }
                    }
                } else {

                }
            } else if(lc%4 == 0) {
                var maxPic = pics.length - 1;
                $cards = $(".card-wrap.finish:not('.cluster')");
                var maxCard = $cards.length - 1;
                if ($cards.length > 0) {
                    var targetId = getRandomInt(0, maxCard);
                    var hIdx = getOldestPic();
                    flipCard(targetId, hIdx);
                }
            }
        }


        lc++;

    }, 2000);

    window.setInterval(function () {

        // update time
        for(var i=0; i<pics.length; i++) {
            if( pics[i].show == false  ) {
                pics[i].age += 1;
            }
        }
        //$('#imgloader').children('.loaded').each(function () {
        //    var time = parseInt($(this).attr('data-time'));
        //    if(time > 100) {
        //        $(this).addClass('age3')
        //    } else if(time > 50) {
        //        $(this).addClass('age2')
        //    } else if(time > 25) {
        //        $(this).addClass('age1')
        //    }
        //    $(this).attr('data-time', time+1);
        //});

    }, 1000);

    $(window).resize(function() {
        //var count = 6;
        //var w = $(window).width() / count;
        //$('div.card').width(w).height(w);
        var wh = $(window).height();
        var h = wh-$('#wrapper').height();
        $("#footer").height(h);
    });

    $(window).trigger('resize');

});