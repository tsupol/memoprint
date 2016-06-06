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
    var maxImg = col*4;
    var isReady = false;
    var newPics = [];
    var users = {};
    var lc = 1;
    var la = 0;
    var init = false;
    var ready = false;
    var lastCi = 1;

    // network
    var max_id = 0;

    var clusters = [];
    clusters[0] = [0, 1, 6, 7, 2];
    clusters[1] = [10, 11, 16, 17, 15];

    function sendMessage(message) {

        var jqxhr = $.ajax(message)
            .done(function (msg) {
                var obj = JSON.parse(msg);
                // media

                if(obj.header == 'media') {
                    var data = obj.data;
                    if(obj.max_id) {
                        max_id = obj.max_id;
                    }

                    //console.log('data', message,  data);
                    var imgLen = imgs.length;
                    for (var i = 0; i < data.length; i++) {
                        var found = false;
                        for (var j = 0; j < imgs.length; j++) {
                            if (imgs[j].id == data[i].id) {
                                found = true;
                                break;
                            }
                        }
                        if (!found) {
                            imgs.push(data[i]);
                            imgs[i].status = 'loading';
                            var $img = $('<img src="' + imgs[i].img_high + '" data-id="' + (imgLen+i) + '" data-time="0" data-new="'+ready+'">');
                            $('#imgloader').append($img);
                            $img
                                .load(function () {
                                    var id = $(this).attr('data-id');
                                    var isNew = $(this).attr('data-new');
                                    $(this).addClass('loaded');
                                    imgs[id].status = 'loaded';
                                    imgs[id].age = 1;
                                    imgs[id].show = false;
                                    //console.log('loaded', id);
                                    //pics.push(imgs[id]);
                                    if(ready && isNew === 'true') {
                                        newPics.push(imgs[id]);
                                    } else {
                                        pics.push(imgs[id]);
                                    }
                                    //console.log('loaded', pics.length, newPics.length);
                                })
                                .error(function () {
                                    var id = $(this).attr('data-id');
                                    $(this).addClass('loaded error');
                                    imgs[id].status = 'error';
                                });

                            //user
                            users[data[i].user_id] = {'username': data[i].username, 'avata': data[i].profile_picture};
                        }
                        //console.log('new image!', newPics.length);
                    }
                    //console.log('users', users);
                }

                // user

                else if(obj.header == 'user') {
                    var data = obj.data;
                    for (var i = 0; i < data.length; i++) {
                        users[data[i].id] = {'username': data[i].user_name, 'avata': data[i].profile_picture};
                        var $pimg = $('<img src="' + imgs[i].profile_picture + '">');
                        $('#profileloader').append($pimg);
                        $pimg
                            .load(function () {
                                var id = $(this).attr('data-id');
                                $(this).remove();
                            })
                            .error(function () {
                                var id = $(this).attr('data-id');
                                $(this).remove();
                            });
                    }
                    //console.log('users 2', users);

                }

                //console.log(data);
            })
            .fail(function () {
                //alert( "error" );
            })
            .always(function () {
                //alert( "complete" );
            });
    }

    sendMessage(host + eventId);

    var $cards = $("#wrapper");
    var $smallCards;

    for(i=0; i<maxImg; i++) {
        $cards.append(
            '<div class="card-wrap empty finish front" data-id="'+-1+'"><div class="card">'+
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
            if (pics[i].show || pics[i].cluster) continue;
            if (pics[i].age > h) {
                h = pics[i].age;
                hIdx = i;
            }
        }
        if(hIdx == -1) {
            for (var i = 0; i < pics.length; i++) {
                if (pics[i].cluster) continue;
                if (pics[i].age > h) {
                    h = pics[i].age;
                    hIdx = i;
                }
            }
        }
        pics[hIdx].age = 0;
        return hIdx;
    }

    function flipCard(targetId, hIdx, delay, isProfile, isSwap) {
        delay = typeof delay !== 'undefined' ? delay : 0;

        var $target = $cards.children().eq(targetId);
        var face = 'front';
        if ($target.hasClass('front')) {
            face = 'back';
        }
        if(isProfile) {
            var userId = pics[hIdx].user_id;
            $target.find('.' + face).css({'background-image': ''});
            $target.find('.'+face).addClass('showpro')
                .html(
                '<img class="profile" src="'+users[userId].avata+
                '" onerror="imgError(this);" /><div class="username">'+users[userId].username+'</div>');
        } else {
            $target.find('.' + face).css('background-image', 'url(' + pics[hIdx].img_high + ')');
            $target.removeClass('profile');
        }
        pics[hIdx].show = true;
        if(isSwap) {
        } else {
            if(parseInt($target.attr('data-id')) != -1) {
                pics[parseInt($target.attr('data-id'))].show = false;
            }
        }
        $target.attr('data-id', hIdx);

        setTimeout(function(){
            if (face == 'front') {
                $target.removeClass('back').addClass('front');
            } else {
                $target.removeClass('front').addClass('back');
            }
            $target
                .removeClass('finish empty')
                .later(3000, function () {
                    $(this).addClass('finish');
                });
        },delay);
    }

    function flipCluster(ci, hIdx, face) {

        if(hIdx == -1 || hIdx >= pics.length) return;

        var $target = $cards.children().eq(clusters[ci][0]);
        if(parseInt($target.attr('data-id')) != -1) {
            pics[parseInt($target.attr('data-id'))].cluster = false;
        }

        var isSwap = false;
        var oldPicId;
        var oldPos;
        if(pics[hIdx].show) {
            oldPicId = parseInt($target.attr('data-id'));
            if (oldPicId != -1) {
                oldPos = $cards.children("[data-id='" + hIdx + "']").index();
                //console.log(oldPos, 0 <= oldPos, oldPos < maxImg, $.inArray(oldPos, clusters[0]), $.inArray(oldPos, clusters[1]) );
                if(0 <= oldPos && oldPos < maxImg && $.inArray(oldPos, clusters[0]) == -1 && $.inArray(oldPos, clusters[1]) == -1) {
                    //console.log('swap');
                    isSwap = true;
                }
            }
        }

        pics[hIdx].show = true;
        pics[hIdx].cluster = true;

        for (var i = 0; i < clusters[ci].length; i++) {
            $target = $cards.children().eq(clusters[ci][i]);
            if (i == 4) {
                flipCard(clusters[ci][i], hIdx, 2 * 200, true, true);
                $target.removeClass('empty').addClass('cluster c' + ci);
            } else {
                if(!face) {
                    var face = 'front';
                    if ($target.hasClass('front')) {
                        face = 'back';
                    }
                }
                if(isSwap) {
                    flipCard(clusters[ci][i], hIdx, i * 200, false, true);
                } else {
                    flipCard(clusters[ci][i], hIdx, i * 200, false, false);
                }
                $target.removeClass('empty').addClass('cluster c' + ci).find('.face.' + face).addClass('big b' + i);
            }
        }

        if(isSwap) {
            flipCard(oldPos, oldPicId, 0, false, true);
        }

    }

    function flip(times) {

        var $sCards;
        if($cards.children('.empty').length > 0) {
            $sCards = $cards.children(".finish:not('.cluster')").not('.empty');
        } else {
            $sCards = $cards.children(".finish:not('.cluster')");
        }
        //console.log('$sCards', $sCards.length);
        var maxCard = $sCards.length - 1;

        for (var i = 0; i < times; i++) {
            if ($cards.length > 0) {
                var targetId = getRandomInt(0, maxCard);
                var hIdx = getOldestPic();
                if(pics[hIdx].show){
                    var oldPicId = parseInt($cards.children().eq($sCards.eq(targetId).index()).attr('data-id'));
                    //console.log('oldPicId',$sCards.eq(targetId).index(), oldPicId);
                    if(oldPicId != -1) {
                        var oldPos = $cards.children("[data-id='"+hIdx+"']").index();
                        flipCard(oldPos, oldPicId, 0, false, true);
                        flipCard($sCards.eq(targetId).index(), hIdx, 0, false, true);
                        //console.log('swap', oldPos, $sCards.eq(targetId).index());
                    } else {
                        flipCard($sCards.eq(targetId).index(), hIdx);
                        //console.log('swap fail', oldPos, $sCards.eq(targetId).index());
                    }
                } else {
                    flipCard($sCards.eq(targetId).index(), hIdx);
                    //console.log('no show', $sCards.eq(targetId).index());
                }
            }
        }

    }

    window.setInterval(function () {

        if(ready) {
            if(pics.length < 3) {

                // do nothing.

            } else if(la >= 20) {

                //console.log('13!');

                // bug fix
                for (var i = 0; i < pics.length; i++) {
                    pics[i].show = false;
                }
                $cards.children().each(function () {
                    var id = parseInt($(this).attr('data-id'));
                    if(id >= 0) {
                        pics[id].show = true;
                    }
                });

                var randDelay = getRandomInt(1,2)*1000;
                var delay1 = 0;
                var delay2 = 0;
                if(getRandomInt(0,1)) {
                    delay1 = randDelay;
                } else {
                    delay2 = randDelay;
                }

                // Cluster
                if(getRandomInt(0,10) > 0) {
                    setTimeout(function() {
                        var ci = (lastCi == 1) ? 0 : 1;
                        lastCi = ci;
                        var hIdx;
                        if(pics.length <= maxImg - 8) {
                            var $sCards = $cards.children(".finish:not('.cluster')").not('.empty');
                            hIdx = parseInt($sCards.eq(getRandomInt(0, $sCards.length-1)).attr('data-id'));
                        } else {
                            hIdx = getOldestPic();
                        }
                        flipCluster(ci, hIdx);
                    }, delay1);
                }

                // Other
                if(getRandomInt(0,10) > 1) {
                    setTimeout(function() {
                        if(pics.length <= maxImg) {
                            flip(1);
                        } else {
                            flip(getRandomInt(1,2));
                        }

                    }, delay2);
                }
                la = 0;
            } else if(la == 10) {

                if(getRandomInt(0,10) > 1) {
                    setTimeout(function() {
                        flip(1);
                    }, getRandomInt(0,2)*1000);
                }

            } else if(la >= 6) {

                //console.log('6!');

                if(newPics.length > 0) {

                    var ci = (lastCi == 1) ? 0 : 1;
                    $target = $cards.children().eq(clusters[ci][0]);
                    lastCi = ci;
                    var oldPicId = parseInt($target.attr('data-id'));
                    pics.push(newPics.pop());
                    flipCluster(ci, pics.length-1);

                    if($cards.children('.empty').length > 0) {
                        if(oldPicId > -1) {
                            flipCard($cards.children('.empty').first().index(), oldPicId, getRandomInt(2,4)*500);
                        }
                    }

                    la = 0;

                }

            }
            la++;
        }

        // Initialize

        else if(!init) {
            //console.log('pic',pics.length);
            if($('#imgloader').children().length == $('#imgloader').children('.loaded').length || lc > 10 || $('#imgloader').children('.loaded').length > maxImg) {
                if(pics.length > 3) {

                    // cluster
                    for (var ci = 0; ci < clusters.length; ci++) {
                        var hIdx = getOldestPic();
                        flipCluster(ci, hIdx, 'back');
                    }

                    //other
                    for (var i = 0; i < $cards.length && i < pics.length-2; i++) {
                    }
                    setTimeout(function() {
                        var $sCards = $cards.children(".finish:not('.cluster')");
                        for (var i = 0; i < $sCards.length && i < pics.length-2; i++) {
                            var hIdx = getOldestPic();
                            pics[hIdx].show = true;
                            flipCard($sCards.eq(i).index(), hIdx, i*100);
                        }

                    }, 1000);

                    init = true;

                } else {
                    if(pics.length > 0) {
                        // cluster
                        for (var ci = 0; ci < clusters.length && ci < pics.length; ci++) {
                            var hIdx = getOldestPic();
                            //pics[hIdx].show = true;
                            flipCluster(ci, hIdx, 'back');
                        }

                        init = true;
                    }
                }
            }

        } else {
            if(!ready) {
                setTimeout(function() {
                    ready = true;
                }, 3000);
            }
        }

        lc++;

    }, 1000);

    // network

    window.setInterval(function () {
        if(ready) {
            sendMessage(host + eventId + '/' + max_id);
        }
    }, 5000);

    window.setInterval(function () {
        if(ready) {
            sendMessage(host + eventId + '/user');
        }
    }, 31500);

    window.setInterval(function () {

        // update time
        var sc = 0;
        var cc = 0;
        for(var i=0; i<pics.length; i++) {
            if( pics[i].show == false ) {
                pics[i].age += 1;
            } else {
                sc++;
            }
            if( pics[i].cluster ) {
                cc++;
            }
        }
        //console.log(sc, cc);

    }, 1000);

    $(window).resize(function() {
        var wh = $(window).height();
        var h = wh-$('#wrapper').height();
        var $footer = $("#footer");
        var dh = $footer.children('div').height();
        $("#footer").height(h).css('padding-top', ((h-dh)/2)+'px');
    });

    $(window).trigger('resize');

});