var app = angular.module('Viewer', ['infinite-scroll','ngRoute', 'IgmUtil']);

app.config(function($routeProvider) {

    $routeProvider
        .when('/', {
            redirectTo: '/feed'
        })
        .when('/feed', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'FeedController as insta'
        })
        .when('/media', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'MediaController as insta'
        })
        .when('/likes', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'LikeController as insta'
        })
        .when('/followers', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'FollowerController as insta'
        })
        .when('/followers/:id', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'FollowerController as insta'
        })
        .when('/followings', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'FollowingController as insta'
        })
        .when('/followings/:id', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'FollowingController as insta'
        })
        .when('/populars', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'PopularController as insta'
        })
        .when('/user/:id', {
            templateUrl: '/memoprint/public/partials/user-media-grid.html',
            controller: 'UserController as insta'
        })
        .when('/tag/:name', {
            templateUrl: '/memoprint/public/partials/tag-media-grid.html',
            controller: 'TagController as insta'
        })
        .when('/search/:text', {
            templateUrl: '/memoprint/public/partials/search.html',
            controller: 'SearchController as insta'
        })
        .otherwise({
            redirectTo: '/'
        });

    // use the HTML5 History API
    //$locationProvider.html5Mode(true);

});

app.controller("BaseController", function ($scope, Instagram) {

    var instagram = this;
    instagram.media = null;
    instagram.loading = true;

    instagram.links = {};
    instagram.show = {};

    instagram.mediaLoadedBase = function(data) {
        instagram.loading = false;
        if(instagram.media === null) {
            //console.log('inside', data);
            instagram.media = data;
        } else {
            //console.log('before',instagram.media.data);
            instagram.media.data = instagram.media.data.concat(data.data);
            instagram.media.pagination = data.pagination;
            //console.log('after',instagram.media.data);
        }
    };

    instagram.loadMoreBase = function() {
        //console.log('next',instagram.media.pagination);
        if(instagram.media.pagination.next_url && !instagram.loading) {
            instagram.loading = true;
            return true;
        }
        return false;
    };

    instagram.links.followers = '#/followers';
    instagram.links.followings = '#/followings';
    instagram.links.media = '#/media';

});

app.controller('MediaController', function($scope, $controller, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});
    instagram.base.loading = true;

    instagram.mediaLoaded = function(data) {
        //console.log('loading',instagram.base.loading);
        instagram.base.mediaLoadedBase(data);
        //console.log('loadinged',instagram.base.loading);
    };

    instagram.loadMore = function() {
        if(instagram.base.loadMoreBase()) {
            Instagram.getNext(instagram.base.media).then(instagram.mediaLoaded);
        }
    };

    Instagram.getMyMedia().then(instagram.mediaLoaded);
});

app.controller('FeedController', function($scope, $controller, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});

    instagram.base.show.user_profile = true;

    instagram.mediaLoaded = function(data) {
        instagram.base.mediaLoadedBase(data);
    };

    instagram.loadMore = function() {
        if(instagram.base.loadMoreBase()) {
            Instagram.getNext(instagram.base.media).then(instagram.mediaLoaded);
        }
    };

    Instagram.getMyFeed().then(instagram.mediaLoaded);
});

app.controller('LikeController', function($scope, $controller, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});

    instagram.base.show.user_profile = true;

    instagram.mediaLoaded = function(data) {
        instagram.base.mediaLoadedBase(data);
    };

    instagram.loadMore = function() {
        if(instagram.base.loadMoreBase()) {
            Instagram.getNext(instagram.base.media).then(instagram.mediaLoaded);
        }
    };

    Instagram.getMyLikes().then(instagram.mediaLoaded);
});

app.controller('SearchController', function($scope, $controller, $routeParams, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});
    instagram.searchTerm = $routeParams.text;

    instagram.mediaLoaded = function(data) {
        instagram.base.mediaLoadedBase(data);
    };

    Instagram.getSearch($routeParams.text).then(instagram.mediaLoaded);
});

app.controller('UserController', function($scope, $controller, $routeParams, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});

    instagram.view = {};

    instagram.mediaLoaded = function(data) {
        instagram.base.mediaLoadedBase(data);
        console.log('inside', instagram.base);
        if(instagram.view.relationship == undefined) {
            if(instagram.base.media.user.relationship.outgoing_status=='none') {
                if(instagram.base.media.user.relationship.incoming_status=='none') {
                    instagram.view.relationship = 'Follow';
                } else {
                    instagram.view.relationship = 'Follow Back';
                }
            } else {
                instagram.view.relationship = 'Unfollow';
            }
        }
    };

    instagram.loadMore = function() {
        if(instagram.base.loadMoreBase()) {
            Instagram.getNext(instagram.base.media).then(instagram.mediaLoaded);
        }
    };

    instagram.base.links.followers = '#/followers/'+$routeParams.id;
    instagram.base.links.followings = '#/followings/'+$routeParams.id;
    instagram.base.links.media = '#/user/'+$routeParams.id;

    Instagram.getUser($routeParams.id).then(instagram.mediaLoaded);
});

app.controller('TagController', function($scope, $controller, $routeParams, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});

    instagram.tags = [];

    var relatedTags = [];

    function getIndex(key) {
        for(var i = 0; i < relatedTags.length; i++) {
            if(relatedTags[i][0] == key) return i;
        }
        return -1;
    }

    function mergeTag(arr) {
        for(var i = 0; i < arr.length; i++) {
            var idx = getIndex(arr[i]);
            if(idx == -1) {
                //console.log('a', idx);
                relatedTags.push([arr[i], 1]);
            } else {
                relatedTags[idx][1] += 1;
            }
        }
        relatedTags.sort(sortFunction);
    }
    function sortFunction(a, b) {
        if (a[1] === b[1]) {
            return 0;
        }
        else {
            return (a[1] > b[1]) ? -1 : 1;
        }
    }

    instagram.mediaLoaded = function(data) {
        instagram.base.mediaLoadedBase(data);
        var newTags = [];
        for(var i = 0; i < instagram.base.media.data.length; i++) {
            newTags = newTags.concat(instagram.base.media.data[i].tags);
        }

        mergeTag(newTags);
        instagram.tags = relatedTags.slice(0, 10);
        console.log(instagram.tags);
    };

    instagram.loadMore = function() {
        if(instagram.base.loadMoreBase()) {
            Instagram.getNext(instagram.base.media,40).then(instagram.mediaLoaded);
        }
    };

    Instagram.getTag($routeParams.name).then(instagram.mediaLoaded);
});

app.controller('FollowerController', function($scope, $controller, $routeParams, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});

    instagram.mediaLoaded = function(data) {
        instagram.base.mediaLoadedBase(data);
    };

    instagram.loadMore = function() {
        if(instagram.base.loadMoreBase()) {
            Instagram.getNext(instagram.base.media).then(instagram.mediaLoaded);
        }
    };

    if($routeParams.id) {
        instagram.base.links.followers = '#/followers/'+$routeParams.id;
        instagram.base.links.followings = '#/followings/'+$routeParams.id;
        instagram.base.links.media = '#/user/'+$routeParams.id;
        Instagram.getFollowers($routeParams.id).then(instagram.mediaLoaded);
    } else {
        Instagram.getMyFollowers().then(instagram.mediaLoaded);
    }

});

app.controller('FollowingController', function($scope, $controller, $routeParams, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});

    instagram.mediaLoaded = function(data) {
        instagram.base.mediaLoadedBase(data);
    };

    instagram.loadMore = function() {
        if(instagram.base.loadMoreBase()) {
            Instagram.getNext(instagram.base.media).then(instagram.mediaLoaded);
        }
    };

    if($routeParams.id) {
        instagram.base.links.followers = '#/followers/'+$routeParams.id;
        instagram.base.links.followings = '#/followings/'+$routeParams.id;
        instagram.base.links.media = '#/user/'+$routeParams.id;
        Instagram.getFollowing($routeParams.id).then(instagram.mediaLoaded);
    } else {
        Instagram.getMyFollowing().then(instagram.mediaLoaded);
    }

});

app.controller('PopularController', function($scope, $controller, Instagram) {

    var instagram = this;
    instagram.base = $controller('BaseController',{$scope: $scope});

    instagram.base.show.user_profile = true;

    instagram.mediaLoaded = function(data) {
        instagram.base.mediaLoadedBase(data);
    };

    instagram.loadMore = function() {
        if(instagram.base.loadMoreBase()) {
            Instagram.getNext(instagram.base.media).then(instagram.mediaLoaded);
        }
    };

    Instagram.getPopulars().then(instagram.mediaLoaded);
});


app.factory('Instagram', function (WebService) {

    var path = 'instagram/';
    return {
        getUser: function (id) {
            return WebService.get(path+'user/'+id);
        },
        getTag: function (name) {
            return WebService.get(path+'tag/'+name);
        },
        getMyMedia: function () {
            return WebService.get(path+'mymedia');
        },
        getMyFeed: function () {
            return WebService.get(path+'myfeed');
        },
        getMyLikes: function () {
            return WebService.get(path+'mylikes');
        },
        getMyFollowers: function () {
            return WebService.get(path+'myfollowers');
        },
        getFollowers: function (id) {
            return WebService.get(path+'followers/'+id);
        },
        getMyFollowing: function () {
            return WebService.get(path+'myfollowings');
        },
        getFollowing: function (id) {
            return WebService.get(path+'followings/'+id);
        },
        getPopulars: function () {
            return WebService.get(path+'populars');
        },
        getSearch: function (text) {
            return WebService.get(path+'search/'+text);
        },
        getNext: function(data, count) {
            if(count === undefined) count = 0;
            var param = {pagination:data.pagination};
            //console.log('load more data',data);
            return WebService.post(path+'next',{next:JSON.stringify(param),count:count});
        }
    }
});