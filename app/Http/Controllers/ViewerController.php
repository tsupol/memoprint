<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Vinkla\Instagram\InstagramManager;
use Session;
use Igm;
use Instagram;

class ViewerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('instagram');
    }

	public function index()
	{
        $data = Igm::getViewerTemplateData();
        return view('instagram.viewer', $data);
	}

    public function getMyInfo()
    {
        if(Session::get('insta_user')) {
            return Session::get('insta_user');
        } else {
            $insta_user = Instagram::getUser();
            Session::set('insta_user', $insta_user);
            return $insta_user;
        }

    }

//    Angular Ajax

    public function getUser($id)
    {
        $user = Instagram::getUser($id);
        $relation = Instagram::getUserRelationship($id);

        if(isset($user->error_type)) {
            return json_encode($user);
        } else {
            $media = Instagram::getUserMedia($id);
            $media->user = $user;
            $media->user->relationship = $relation->data;
            return json_encode($media);
        }
    }

    public function getTag($name)
    {
        $tag = Instagram::getTag($name);
        $media = Instagram::getTagMedia($name,20);
        $media->tag = $tag;
        return json_encode($media);
    }

    public function search($search)
    {
        $tag = Instagram::searchTags($search);
        $user = Instagram::searchUser($search, 20);
        $result = new \stdClass;
        $result->tag = $tag;
        $result->user = $user;
        return json_encode($result);
    }

    public function getMyFeed()
    {
        $media = Instagram::getUserFeed(32);
        return json_encode($media);
    }

    public function getMyMedia()
    {
        $user = $this->getMyInfo();
        $media = Instagram::getUserMedia('self', 32);
        $media->user = $user;
        $media->user->self = true;
        return json_encode($media);
    }

    public function getMyLikes()
    {
        $media = Instagram::getUserLikes(32);
        return json_encode($media);
    }

    public function getMyFollowers()
    {
        $user = $this->getMyInfo();
        $media = Instagram::getUserFollower();
        $media->user = $user;
        $media->user->self = true;
        return json_encode($media);
    }

    public function getFollowers($id)
    {
        $user = Instagram::getUser($id);
        if(isset($user->error_type)) {
            return json_encode($user);
        } else {
            $media = Instagram::getUserFollower($id);
            $media->user = $user;
            return json_encode($media);
        }
    }

    public function getMyFollowings()
    {
        $user = $this->getMyInfo();
        $media = Instagram::getUserFollows();
        $media->user = $user;
        $media->user->self = true;
        return json_encode($media);
    }

    public function getFollowings($id)
    {
        $user = Instagram::getUser($id);
        if(isset($user->error_type)) {
            return json_encode($user);
        } else {
            $media = Instagram::getUserFollows($id);
            $media->user = $user;
            return json_encode($media);
        }
    }

    public function getPopular()
    {
        $media = Instagram::getPopularMedia();
        return json_encode($media);
    }

    public function postNext(Request $request)
    {
        $next = $request->get('next');
        $count = $request->get('count');
        $media = Instagram::pagination(json_decode($next), $count);
        return json_encode($media);
    }

}
