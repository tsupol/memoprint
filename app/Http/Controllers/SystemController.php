<?php namespace App\Http\Controllers;

use App\Commands\GetMedia;
use App\EventMedia;
use App\Fan;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Media;
use App\Mvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CreateBrandRequest;
use Event;
use Igm;
use Auth;
use Artisan;
use Session;
use Redis;
use Log;
use Instagram;
use DB;
use Storage;

class SystemController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
    }

    public function runSchedule() {
        Artisan::call('schedule:run');
        echo 'done - schedule:run';
    }

    public function testCode() {
        $mediaId = "1001256518524605940_14878332";
        $m = Media::find($mediaId);
        $m->file_path = "dfdf";
        $m->save();
        dd($m);
        //$storePath =  Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        //dd(public_path());
        //dd($storePath);
        $source = "https://scontent.cdninstagram.com/hphotos-xaf1/t51.2885-15/e15/11356887_907720152620896_825026631_n.jpg";
        $tokens = explode('/', $source);
        dd($tokens[sizeof($tokens) - 1]);
        $destination = public_path()."/insta_media/555.jpg";
        try {
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
            $data = file_get_contents($source, false, stream_context_create($arrContextOptions));

            $dirName = dirname($destination);
            if (!is_dir($dirName))
            {
                mkdir($dirName, 0755, true);
            }
            $handle = fopen($destination, "w");
            fwrite($handle, $data);
            fclose($handle);
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function testGetMedia() {
        $nowStr = Carbon::now()->toDateTimeString();

        $mediaCount = 32;
        $dayCount = 1;
        $mediaUpdateCount = 0;
        $mediaGetLimit = 192;
        $firstMediaGet = 192;

        try {

            $events = Mvent::whereIsActive(true)->get();
            foreach($events as $event) {
                $startTime = Carbon::now();

                // is First Time
                $bFirstTime = (EventMedia::whereEventId($event->id)->count() == 0);
                if($bFirstTime) {
                    $mediaGetLimit = $firstMediaGet;
                }

                Instagram::setAccessToken(Igm::getWorkerAccessToken());

                $tagName = $event->tag;

                //Log::notice("[current tag] {$tagName}");

                $mediaStartTime = $event->start_at;

                $expiredCount = 0;
                $mediaGetCount = 0;


                $mediaUpdated = 0;

                // debug
                $loopCount = 0;
                $continue = true;

                //if(empty($mediaList->data)) continue;

                $mediaList = null;
                $mediaArr = [];
                do {

                    if($mediaList == null) {
                        $mediaList = Instagram::getTagMedia($tagName, $mediaCount);
                    } else {
                        $mediaList = Instagram::pagination($mediaList, 32);
                    }

                    $loopCount++;
                    foreach ($mediaList->data as $media) {

                        //Event::fire(new Notification("--- media : " . $media->id));

                        if ($expiredCount > 2 || ($mediaGetCount >= $mediaGetLimit)) {
                            $continue = false;
                            break;
                        }

                        $createdTime = Carbon::createFromTimestamp($media->created_time, 'Asia/Bangkok');

                        if ($createdTime->lt($mediaStartTime)) {
                            $expiredCount++;
                            continue;
                        } else {
                            $mediaArr[] = $media;
                            $mediaGetCount++;
                            // debug
//                            echo('<img width="100" height="100" src="'.$media->images->low_resolution->url.'"><br/>');
//                            echo('<p>'.$mediaGetCount.'</p>');
                        }
                    }
                    //log::info("[debug] tag={$tagName} media={$mediaGetCount}");
                } while (isset($mediaList->pagination->next_url) && $continue && $mediaGetCount < $mediaGetLimit);

                for($k = count($mediaArr)-1; $k >= 0; $k--) {

                    $media = $mediaArr[$k];
                    $findMedia = Media::find($media->id);
                    if (is_null($findMedia)) {
                        Media::createByMedia($media);

                        $mediaUpdateCount++;
                        $mediaUpdated++;
                    }

                    // fan update

                    $fanId = $media->user->id;
                    $fan = Fan::find($fanId);
                    if (is_null($fan)) {
                        Fan::createByMedia($media->user);
                    } else {
                        $fan->profile_picture = $media->user->profile_picture;
                        $fan->save();
                    }

                    // event media

                    if (is_null(EventMedia::whereMediaId($media->id)->whereEventId($event->id)->first())) {
                        //echo "{$media->id} - {$event->id}<br/>";
                        $evm = new EventMedia();
                        $evm->media_id = $media->id;
                        $evm->event_id = $event->id;
                        $evm->is_use = true;
                        $evm->save();
                    }
                    $mediaGetCount++;
                }

                $dif = $startTime->diff(Carbon::now());
                $runTime = $dif->h.":".$dif->i.":".$dif->s;

                dd("[debug] runtime={$runTime} tag={$tagName} loop={$loopCount} media={$mediaGetCount}");
//                Log::info("[debug] runtime={$runTime} tag={$tagName} loop={$loopCount} media={$mediaGetCount}");
            }


            if($mediaUpdateCount > 0) {

                echo "{$mediaUpdateCount} media";

                //$dif = $startTime->diff(Carbon::now());
                //$runTime = $dif->h.":".$dif->i.":".$dif->s;
                //$eventStr = $mediaUpdateCount . " media updated.";

                //Event::fire(new TaskEvent($taskType, $eventStr, $runTime));
                //FinishedTask::createFinishedTask($taskType, $eventStr);
            }

        } catch (\Exception $exception) {

            Log::notice("[Error] {$exception->getMessage()}");
        }

    }

}
