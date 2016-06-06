<?php namespace App\Commands;

use App\EventMedia;
use App\Fan;
use App\Media;
use App\Mvent;
use Carbon\Carbon;
use Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Igm;
use Instagram;

class GetMedia extends Command implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $mediaGetLimit;

    public function __construct($mediaGetLimit = 32)
    {
        $this->mediaGetLimit = $mediaGetLimit;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $nowStr = Carbon::now()->toDateTimeString();

        $mediaCount = 32;
        $dayCount = 1;
        $mediaUpdateCount = 0;
        $mediaGetLimit = $this->mediaGetLimit;
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

//                dd("[debug] runtime={$runTime} tag={$tagName} loop={$loopCount} media={$mediaGetCount}");
                Log::info("[debug] runtime={$runTime} tag={$tagName} loop={$loopCount} media={$mediaGetCount}");
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

        //Log::info("finish [getNewMedia][{$nowStr}]");

    }


}
