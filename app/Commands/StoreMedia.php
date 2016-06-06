<?php namespace App\Commands;

use App\Commands\Command;

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
use DB;

class StoreMedia extends Command implements ShouldQueue
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
        Log::info("enter [StoreMedia][{$nowStr}]");
        // Settings

        $mediaCount = 32;
        $dayCount = 1;
        $mediaUpdateCount = 0;
        $mediaGetLimit = $this->mediaGetLimit;
        $downloadCount = 0;

        try {

            $startTime = Carbon::now();

            Instagram::setAccessToken(Igm::getWorkerAccessToken());

            $mediaList = DB::table('media')
                ->where('file_path', '=', '')
                ->join('event_media', 'media.id', '=', 'event_media.media_id')
                ->get();

            foreach ($mediaList as $media) {
                $source = $media->img_high;
                $mediaId = $media->media_id;
                $destination = public_path()."/insta_media/".$media->event_id."/".$mediaId.".jpg";

                //Log::info("start [StoreMedia] ".$media->event_id."/".$mediaId.".jpg");

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

                    $m = Media::find($mediaId);
                    $m->file_path = $media->event_id."/".$mediaId.".jpg";
                    $m->save();

                    $downloadCount++;
                } catch (\Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }

        } catch (\Exception $exception) {
            Log::notice("[Error] {$exception->getMessage()}");
        }

        Log::info("finish [StoreMedia][{$nowStr}] {$downloadCount} media downloaded.");

    }


}
