var app = angular.module('Dashboard', ['ngRoute','angularMoment', 'IgmUtil']);

app.config(function($routeProvider) {

    $routeProvider
        .when('/', {
            templateUrl: '/memoprint/public/partials/dashboard.html',
            controller: 'DashboardController as dashboard'
        }).otherwise({
            redirectTo: '/'
        });

    // use the HTML5 History API
    //$locationProvider.html5Mode(true);

});

app.constant('angularMomentConfig', {
    preprocess: 'unix', // optional
    timezone: 'Asia/Bangkok' // optional
});

app.controller('DashboardController', function($scope, socket, RestService) {

    var dashboard = this;
    dashboard.data = {};
    dashboard.data.message = [];
    dashboard.data.task = [];
    dashboard.data.activeTime = [];
    dashboard.data.followerChart = [];

    dashboard.loaded = {};
    dashboard.loaded.activeTime = false;
    dashboard.loaded.followerChart = false;
    dashboard.url = {
        viewer: 'http://103.245.167.79/memoprint/public/viewer#/'
    };
    var logIdx = 1;

    dashboard.brandId = -1;

    // Show / Hide
    dashboard.view = {
        like: true,
        follow: true,
        unfollow: true,
        following: true,
        unfollowing: true,
        followback: true
    };

    socket.on('brand.notice', function(data) {
        if(dashboard.brandId != 1) return;
        console.log(logIdx++, data);
    });

    socket.on('brand.like', function(data) {
        var dat = JSON.parse(data);
        if(dat.brandId != dashboard.brandId) return;
        dat.type = 6;
        console.log('like',dat);
        dashboard.data.message.unshift(dat);
    });

    socket.on('brand.follow', function(data) {
        var dat = JSON.parse(data);
        if(dat.brandId != dashboard.brandId) return;
        //dat.type = 2;
        //dat.type = data[i].type;
        console.log('follow',dat);
        if(dat.type == 1) dat.action = 'followed';
        else if(dat.type == 2) dat.action = 'unfollowed';
        else if(dat.type == 3) dat.action = 'was followed by';
        else if(dat.type == 4) dat.action = 'was unfollowed by';
        else if(dat.type == 5) dat.action = 'was followed back by';
        dashboard.data.message.unshift(dat);
    });

    socket.on('task', function(data) {
        var dat = JSON.parse(data);
        if(dat.brandId != dashboard.brandId) return;
        //console.log(dat);
        dashboard.data.task.unshift(dat);
    });

    dashboard.activityLoaded = function(data) {

        for(var i = 0; i < data.length; i++) {
            var dat = JSON.parse(data[i].message);
            //console.log(dat);
            dat.type = data[i].type;
            if(dat.type == 1) dat.action = 'followed';
            else if(dat.type == 2) dat.action = 'unfollowed';
            else if(dat.type == 3) dat.action = 'was followed by';
            else if(dat.type == 4) dat.action = 'was unfollowed by';
            else if(dat.type == 5) dat.action = 'was followed back by';
            if(dat.type >= 20) {
                dashboard.data.task.push(dat);
            } else {
                dashboard.data.message.push(dat);
            }
            //dat.type = 2;
        }
    };

    dashboard.brandDataLoaded = function(data) {
        dashboard.brandId = data.brandId;
    };

    dashboard.activeTimeLoaded = function(data) {
        var sum = data.sum.split(",");
        var result = [];
        for (i = 0; i < sum.length; i++) {
            result[i] = [i+':00',parseInt( sum[i])];
        }
        dashboard.data.activeTime = result;
        dashboard.loaded.activeTime = true;
    };

    dashboard.followerChartLoaded = function(data) {
        //var sum = data.sum.split(",");
        //console.log('data', data);
        if(data.length > 0) {
            dashboard.data.followerChart[0] = ['Day', 'Follower', 'Following', 'Lost Follower'];
            console.log('dfasd' , ['Day', 'Follower', 'Following', 'Lost Follower']);
            for (var i = 0; i < data.length; i++) {
                //
                dashboard.data.followerChart[i+1] = [data[i].day, parseInt(data[i].follower), parseInt(data[i].following), parseInt(data[i].lost_follower)];
                //dashboard.data.followerChart.follower[i] = parseInt(data[i].follower);
                //dashboard.data.followerChart.following[i] = parseInt(data[i].following);
                //dashboard.data.followerChart.lostFollower[i] = parseInt(data[i].lost_follower);
            }
            //dashboard.data.followerChart.start = Date.UTC(2015, 4, 22, 0, 0, 0);
            //console.log('day', Date.parse(data[0].day));
        }
        console.log('charts', dashboard.data.followerChart);
        dashboard.loaded.followerChart = true;
    };

    RestService.getBrandData().then(dashboard.brandDataLoaded);
    RestService.getRecentActivity().then(dashboard.activityLoaded);
    RestService.getActiveTime().then(dashboard.activeTimeLoaded);
    RestService.getFollowerChart().then(dashboard.followerChartLoaded);

});

app.directive('activity', function() {
    return {
        restrict: 'E',
        templateUrl: function(elem, attr){
            if(attr.type) return '/memoprint/public/widgets/dashboard/activity-'+attr.type+'.html';
            return '/memoprint/public/widgets/dashboard/activity.html';
        },
        link: function(scope, iElement, attrs) {
            //attrs references any attributes on the directive element in html

            //iElement is the actual DOM element of the directive,
            //so you can bind to it with jQuery

            $(iElement).find('[data-rel^="scroll"]').mCustomScrollbar({
                set_height: function(){$(this).css('max-height',$(this).data('scrollheight')); return $(this).data('scrollheight'); },
                mouseWheel:"auto",
                autoDraggerLength:true,
                autoHideScrollbar:true,
                advanced:{
                    updateOnBrowserResize:true,
                    updateOnContentResize:true
                }
            });

            $(iElement).find('div.filter-btns>div').addClass('active');

            $(iElement).on('click', 'div.filter-btns>div', function(e) {
                var target = $(e.target);
                if(!target.hasClass('menu')) target = target.parent();
                var type = target.attr('data-type');
                var bShow = target.toggleClass('active').hasClass('active');
                if(bShow) {
                    $(iElement).find('ul.list-wrapper>li.type'+type).show();
                } else {
                    $(iElement).find('ul.list-wrapper>li.type'+type).hide();
                }
                //console.log(e.data.type);
            })

        }
    };
});

app.directive('activetime', function() {
    return {
        restrict: 'E',
        templateUrl: '/memoprint/public/widgets/dashboard/activetime.html',
        link: function(scope, iElement, attrs) {

            $.plot(
                $(iElement).find("#example-bar-white"), [{
                    data: scope.dashboard.data.activeTime,
                    //           color: "rgba(31,174,102, 0.8)",
                    color: "rgba(255,255,255,0.25)" ,
                    shadowSize: 0,
                    bars: {
                        show: true,
                        lineWidth: 0,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 1
                            }, {
                                opacity: 1
                            }]
                        }
                    }
                }], {
                    series: {
                        bars: {
                            show: true,
                            barWidth: 0.9,
                            align: "center"
                        }
                    },
                    grid: {
                        show: true,
                        hoverable: true,
                        borderWidth: 0
                    },
                    yaxis: {
                        min: 0,
                        show: false
                    },
                    xaxis: {
                        mode: "categories",
                        tickLength: 0,
                        font: {
                            color: "#FFFFFF",
                        }
                    }
                });


        }
    };

});

app.directive('followerChart', function() {
    return {
        restrict: 'E',
        templateUrl: '/memoprint/public/widgets/dashboard/follower-chart.html',
        link: function(scope, iElement, attrs) {

            //$.getJSON('http://www.highcharts.com/samples/data/jsonp.php?filename=analytics.csv&callback=?', function (data) {
            //    console.log('asdf', data);
            //});

            $(function () {
                $(iElement).find("#followerChart").highcharts({
                    data: {
                        rows: scope.dashboard.data.followerChart,
                        firstRowAsNames: true
                    },
                    chart: {
                        type: 'area'
                    },
                    title: {
                        text: 'Followers'
                    },
                    xAxis: {
                        type: 'datetime'
                        //minRange: 7 * 24 * 3600000 // seven days
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Follower'
                    }, {
                        name: 'Following'
                        //data: scope.dashboard.data.followerChart.following
                    }, {
                        name: 'Lost Follower'
                        //data: scope.dashboard.data.followerChart.lostFollower
                    }]
                });
            });

            //scope.dashboard.data.followerChart.following
        }
    };

});

app.factory('socket', function ($rootScope) {
    var socket = io.connect('http://103.245.167.79:3003/');
    return {
        on: function (eventName, callback) {
            socket.on(eventName, function () {
                var args = arguments;
                $rootScope.$apply(function () {
                    callback.apply(socket, args);
                });
            });
        },
        emit: function (eventName, data, callback) {
            socket.emit(eventName, data, function () {
                var args = arguments;
                $rootScope.$apply(function () {
                    if (callback) {
                        callback.apply(socket, args);
                    }
                });
            })
        }
    };
});

app.factory('RestService', function (WebService) {

    return {
        getRecentActivity: function (id) {
            return WebService.get('brand/activity/recent');
        },
        getActiveTime: function () {
            return WebService.get('brand/stat/activetime');
        },
        getBrandData: function () {
            return WebService.get('brand/data');
        },
        getFollowerChart: function () {
            return WebService.get('brand/stat/follow');
        }
    }

});
