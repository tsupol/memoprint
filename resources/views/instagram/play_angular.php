<div ng-app="igm">
    <div ng-controller="MediaController as insta">

        <!-- Media Loop -->
        <div class="preloader loading">
            <span class="slice"></span>
            <span class="slice"></span>
            <span class="slice"></span>
            <span class="slice"></span>
            <span class="slice"></span>
            <span class="slice"></span>
        </div>

        <div class="vd_content-section clearfix">
            <div class="insta-wrapper row" infinite-scroll="insta.loadMore()" infinite-scroll-disabled='insta.loading' infinite-scroll-distance="0">
                <div class="col-sm-4 col-md-2" ng-repeat="media in insta.media.data">
                    <div class="elem">
                        <div class="elem-header">
                            <img ng-src="{{ media.user.profile_picture }}">
                            <p>{{ media.user.username }}</p>
                        </div>
                        <a class="elem-img" href="{{ media.link }}">
                            <img ng-src="{{ media.images.thumbnail.url }}">
                        </a>
                        <div class="elem-footer">
                            <i class="fa fa-fw fa-heart"></i>{{ media.likes.count }}
                            <i class="fa fa-fw fa-comment"></i>{{ media.comments.count }}
                        </div>
                    </div>
                </div>
            </div>
            <button ng-show="insta.media.pagination.next_url" ng-click="insta.loadMore()">More</button>
            <div class="clearfix"></div>
        </div>

        <!-- End Media Loop -->

        <div ng-show="insta.loading" id="loader-cont">
            <div class="spinwrap">
                <div class="spinner">
                    <div class="cube1"></div>
                    <div class="cube2"></div>
                </div>
            </div>
        </div>

        <!--<h1>url : {{ insta.media.pagination.next_url }}</h1>-->
        <!--<h1>pagination : {{ insta.media.pagination.next_max_id }}</h1>-->

    </div>
</div>