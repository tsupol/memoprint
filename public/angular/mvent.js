var app = angular.module('Mvent', ['infinite-scroll','ngRoute', 'IgmUtil']);

app.config(function($routeProvider) {

    $routeProvider
        .when('/', {
            templateUrl: '/memoprint/public/partials/choose-grid.html',
            controller: 'ChooseController as ctrl'
            //redirectTo: '/feed'
        })
        //.when('/choose', {
        //    templateUrl: '/memoprint/public/partials/user-media-grid.html',
        //    controller: 'FeedController as insta'
        //})
        .otherwise({
            redirectTo: '/'
        });

    // use the HTML5 History API
    //$locationProvider.html5Mode(true);

});

app.controller("ChooseController", function ($scope, Instagram) {

    var me = this;
    me.media = null;
    me.loading = true;

    me.links = {};
    me.show = {};

    me.mediaLoaded = function(data) {
        me.loading = false;
        if(me.media === null) {
            //console.log('inside', data);
            me.media = data;
        } else {
            //console.log('before',me.media.data);
            me.media.data = me.media.data.concat(data.data);
            me.media.pagination = data.pagination;
            //console.log('after',me.media.data);
        }
    };

    me.changeVisible = function (media, bVisible) {
        Instagram.setVisible(media.id, media.media_id, bVisible).then(function() {
            media.is_use = bVisible;
        });
        //console.log('bShow', bShow);
    };

    me.loadMore = function() {
        //console.log('next',me.media.pagination);
        if(me.media.pagination && !me.loading) {
            me.loading = true;
            // Instagram.getNextVideo(window.event_id, me.media.pagination).then(me.mediaLoaded);
            Instagram.getNextMedia(window.event_id, me.media.pagination).then(me.mediaLoaded);
        }
    };

    // Instagram.getVideo(window.event_id).then(me.mediaLoaded);
    Instagram.getMedia(window.event_id).then(me.mediaLoaded);

});

app.factory('Instagram', function (WebService) {

    var path = 'media/';
    return {
        getVideoByTag: function (tagName) {
            return WebService.get(path+'tags?tags='+tagName);
        },
        getNextVideo: function (eventId, min_id) {
            return WebService.get(path+'video/'+eventId+'?min_id='+min_id);
        },
        getVideo: function (eventId) {
            return WebService.get(path+'video/'+eventId);
        },
        getNextMedia: function (eventId, min_id) {
            return WebService.get(path+'media/'+eventId+'?min_id='+min_id);
        },
        getMedia: function (eventId) {
            return WebService.get(path+'media/'+eventId);
        },
        setVisible: function (event_id, media_id, visible) {
            return WebService.post(path+'visible?event_id='+event_id+'&media_id='+media_id+'&visible='+visible);
        },
        getNext: function(data, count) {
            if(count === undefined) count = 0;
            var param = {pagination:data.pagination};
            //console.log('load more data',data);
            return WebService.post(path+'next',{next:JSON.stringify(param),count:count});
        }
    }
});


//app.controller('MediaController', function($scope, $controller, Instagram) {
//
//    var instagram = this;
//    instagram.base = $controller('BaseController',{$scope: $scope});
//    instagram.base.loading = true;
//
//    instagram.mediaLoaded = function(data) {
//        //console.log('loading',instagram.base.loading);
//        instagram.base.mediaLoadedBase(data);
//        //console.log('loadinged',instagram.base.loading);
//    };
//
//    instagram.loadMore = function() {
//        if(instagram.base.loadMoreBase()) {
//            Instagram.getNext(instagram.base.media).then(instagram.mediaLoaded);
//        }
//    };
//
//    Instagram.getMyMedia().then(instagram.mediaLoaded);
//});