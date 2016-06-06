<?php namespace App\Http\Controllers;

use App\Commands\GetMedia;
use App\EventMedia;
use App\Fan;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CreateBrandRequest;
use App\Handlers\Events\NotificationHandler;
use Event;
use Igm;
use Auth;
use Session;
use Redis;
use Log;
use Instagram;
use DB;
use Input;
use Response;

class ApiController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('instagram');
    }

    public function index()
    {
        Log::notice('haha');
        $this->dispatch(new GetMedia());
    }

    public function getJson($event_id, $min_id = 0)
    {

        $mediaList = DB::table('media as m')
            ->where('media_type', '=', 'image')
            ->join('event_media as e', function ($join) use ($event_id, $min_id) {
                $join->on('m.id', '=', 'e.media_id')
                    ->where('e.event_id', '=', $event_id)
                    ->where('e.is_use', '=', true)
                    ->where('e.id', '>', $min_id);
            })
            ->join('fans as f', function ($join) {
                $join->on('f.id', '=', 'm.user_id');
            })
            ->select('e.id', 'm.id as media_id', 'm.img_low', 'm.img_high', 'm.user_id', 'm.tags', 'm.caption', 'm.created_time', 'f.user_name as username', 'f.full_name', 'f.profile_picture')
            ->take(250)->orderBy('e.id', 'desc')->get();

        //dd($mediaList);
        $msg = new \stdClass;
        $msg->header = "media";

        if ($min_id == 0) $msg->type = "all";
        else $msg->type = "new";
        if (count($mediaList) > 0) {
            $msg->max_id = $mediaList[0]->id;
        }

        $msg->data = $mediaList;

        return json_encode($msg);
    }

    public function getUserJson($event_id)
    {

        $fanList = DB::table('media as m')
            ->where('media_type', '=', 'image')
            ->join('event_media as e', function ($join) use ($event_id) {
                $join->on('m.id', '=', 'e.media_id')
                    ->where('e.event_id', '=', $event_id)
                    ->where('e.is_use', '=', true);
            })
            ->join('fans as f', function ($join) {
                $join->on('f.id', '=', 'm.user_id');
            })
            ->select('f.id', 'f.user_name', 'f.profile_picture')->distinct()->get();

        //dd($mediaList);
        $msg = new \stdClass;
        $msg->header = "user";

        $msg->data = $fanList;

        return json_encode($msg);
    }

    public function debug()
    {
        Instagram::setAccessToken(Igm::getWorkerAccessToken());
        $mediaList = Instagram::getTagMedia("qazaza555", 32);
        dd($mediaList);

        dd(get_current_user());
    }

    public function getWall()
    {
        return view('event.wall');
    }

    // ------------------------------------
    // ------------------------------------
    // Dance Fat Off
    // ------------------------------------
    // ------------------------------------

    public function getVideo()
    {
        $take = (Input::has('take'))? Input::get('take') : 6;
        $tags = explode(',', Input::get('tags'));
        $data = [];
        if(!empty($tags)) {
            $query = DB::table('events')
                ->where('events.tag', '=', $tags[0])
                ->join('event_media', function ($join) {
                    $join->on('events.id', '=', 'event_media.event_id')
                        ->where('event_media.is_use', '=', true);
                })
                ->join('media', function ($join) {
                    $join->on('media.id', '=', 'event_media.media_id')
                        ->where('media.media_type', '=', 'video');
                })
                ->join('fans', function ($join) {
                    $join->on('fans.id', '=', 'media.user_id');
                });

            $total = $query->selectRaw('COUNT(event_media.id) as total')->first()->total;

//            if(Input::has('max_id')) {
//                $query->where('event_media.id', '>', Input::get('max_id'))
//                      ->orderBy('media.created_time', 'desc');
//            } else if(Input::has('min_id')) {
//                $query->where('event_media.id', '<', Input::get('min_id'))
//                      ->orderBy('media.created_time', 'asc');
//            }
            if(Input::has('max_id')) {
                $query->where('event_media.id', '>', Input::get('max_id'))
                      ->orderBy('event_media.id', 'asc');
            } else if(Input::has('min_id')) {
                $query->where('event_media.id', '<', Input::get('min_id'))
                      ->orderBy('event_media.id', 'desc');
            }
            $data = $query->select('vid_low','vid_high','img_low','img_high','img_width','img_height','vid_width','vid_height','event_media.media_id','events.id','event_media.is_use','fans.user_name','fans.full_name','fans.profile_picture','event_media.id as ev_id')
                          ->take($take)->get();

            $minId = false;
            $maxId = false;
            if(Input::has('max_id')) {
                $data = array_reverse($data);
            }
            if(count($data) > 0) {
                $minId = $data[count($data)-1]->ev_id;
                $maxId = $data[0]->ev_id;
            }
        }
        echo    $_GET['callback'] . '(' . json_encode([
                'data' => $data,
                'min_id' => $minId,
                'max_id' => $maxId,
                'total' => $total,
            ]) . ')';
//        return Response::json($data, 200, array('Content-Type' => 'application/javascript'));
    }

    public function getMedia()
    {
        $take = (Input::has('take'))? Input::get('take') : 6;
        $tags = explode(',', Input::get('tags'));
        $data = [];
        if(!empty($tags)) {
            $query = DB::table('events')
                ->where('events.tag', '=', $tags[0])
                ->join('event_media', function ($join) {
                    $join->on('events.id', '=', 'event_media.event_id')
                        ->where('event_media.is_use', '=', true);
                })
                ->join('media', function ($join) {
                    $join->on('media.id', '=', 'event_media.media_id');
                })
                ->join('fans', function ($join) {
                    $join->on('fans.id', '=', 'media.user_id');
                });

            $total = $query->selectRaw('COUNT(event_media.id) as total')->first()->total;

//            if(Input::has('max_id')) {
//                $query->where('event_media.id', '>', Input::get('max_id'))
//                      ->orderBy('media.created_time', 'desc');
//            } else if(Input::has('min_id')) {
//                $query->where('event_media.id', '<', Input::get('min_id'))
//                      ->orderBy('media.created_time', 'asc');
//            }
            if(Input::has('max_id')) {
                $query->where('event_media.id', '>', Input::get('max_id'))
                      ->orderBy('event_media.id', 'asc');
            } else if(Input::has('min_id')) {
                $query->where('event_media.id', '<', Input::get('min_id'))
                      ->orderBy('event_media.id', 'desc');
            }
            $data = $query->select('vid_low','vid_high','img_low','img_high','img_width','img_height','vid_width','vid_height','event_media.media_id','events.id','event_media.is_use','fans.user_name','fans.full_name','fans.profile_picture','event_media.id as ev_id')
                          ->take($take)->get();

            $minId = false;
            $maxId = false;
            if(Input::has('max_id')) {
                $data = array_reverse($data);
            }
            if(count($data) > 0) {
                $minId = $data[count($data)-1]->ev_id;
                $maxId = $data[0]->ev_id;
            }
        }
        echo    $_GET['callback'] . '(' . json_encode([
                'data' => $data,
                'min_id' => $minId,
                'max_id' => $maxId,
                'total' => $total,
            ]) . ')';
//        return Response::json($data, 200, array('Content-Type' => 'application/javascript'));
    }


}
