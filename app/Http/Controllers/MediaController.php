<?php namespace App\Http\Controllers;

use App\EventMedia;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Vinkla\Instagram\InstagramManager;
use Session;
use Igm;
use Instagram;
use Input;
use DB;

class MediaController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getTags()
    {
        $tags = explode(',', Input::get('tags'));
        $data = [];
        if(!empty($tags)) {
            $data = DB::table('events')
                ->select('img_low','img_high','event_media.media_id','events.id','event_media.is_use','fans.user_name','fans.full_name','fans.profile_picture')
                ->where('events.tag', '=', $tags[0])
                ->join('event_media', function ($join) {
                    $join->on('events.id', '=', 'event_media.event_id');
//                         ->where('event_media.user_id', '>', 5);
                })
                ->join('media', function ($join) {
                    $join->on('media.id', '=', 'event_media.media_id')
                         ->where('media.media_type', '=', 'video');
                })
                ->join('fans', function ($join) {
                    $join->on('fans.id', '=', 'media.user_id');
                })->take(32)->get();
        }
        return json_encode(['data' => $data, 'pagination' => '']);
    }

    public function getVideo($id)
    {
        $data = [];
        if(is_numeric($id)) {
            $query = DB::table('events')
                ->select('img_low','img_high','event_media.media_id','events.id','event_media.is_use','fans.user_name','fans.full_name','fans.profile_picture','event_media.id as ev_id')
                ->where('events.id', '=', $id)
                ->join('event_media', function ($join) {
                    $join->on('events.id', '=', 'event_media.event_id');
//                         ->where('event_media.user_id', '>', 5);
                })
                ->join('media', function ($join) {
                    $join->on('media.id', '=', 'event_media.media_id')
                         ->where('media.media_type', '=', 'video');
                })
                ->join('fans', function ($join) {
                    $join->on('fans.id', '=', 'media.user_id');
                })->orderBy('event_media.id', 'desc');
//                })->orderBy('media.created_time', 'desc');

            if(Input::has('min_id')) {
                $query->where('event_media.id', '<', Input::get('min_id'));
            }

            $data = $query->take(32)->get();
            $minId = false;
            if(count($data) > 0) {
                $minId = $data[count($data)-1]->ev_id;
            }
        }
        return json_encode(['data' => $data, 'pagination' => $minId]);
    }


    public function postVisible()
    {
        $data = EventMedia::where('media_id', '=', Input::get('media_id'))
                ->where('event_id', '=', Input::get('event_id'))
                ->update(['is_use' => (Input::get('visible') == 'true')]);

        return json_encode($data);
    }
}
