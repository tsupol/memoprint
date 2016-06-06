<?php namespace App\Http\Controllers;

use App\EventMedia;
use App\Fan;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Media;
use App\Mvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Handlers\Events\NotificationHandler;
use Event;
use Igm;
use Auth;
use Session;
use Redis;
use Log;
use Instagram;
use DB;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller {

    public function __construct()
    {
        //Log::info('before middle');
        $this->middleware('auth');
//        $this->middleware('instagram');
    }

    public function index()
    {
//        $events = Mvent::whereIsActive(true)->orderBy('name', 'desc')->get();
        $events = Mvent::orderBy('created_at', 'desc')->get();
        $data = Igm::getViewerTemplateData();
        $data["events"] = $events;

        return view('event.index', $data);
        //return redirect('event/'.Session::get('brand_id').'/edit');
    }

    public function getWall()
    {
        return view('event.wall');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $data = Igm::getEventTemplateData();
        return view('event.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateEventRequest $request)
    {
        // $request->all();
        $sf = $request->get('start-finish');
        $dts = explode(' - ', $sf);
        $request['start_at'] = Carbon::createFromFormat('m/d/Y h:i A', $dts[0]);
        $request['finish_at'] = Carbon::createFromFormat('m/d/Y h:i A', $dts[1]);
        $is_active = $request->get('is_active');
        if($request->get('is_active')) {
            $request['is_active'] = 1;
        } else {
            $request['is_active'] = 0;
        }
//        dd($request->all());
        Mvent::create($request->all());
        Mvent::all()->first();
        return redirect('event');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Igm::getViewerTemplateData();
        $event = Mvent::find($id);
        $result = array_merge($data, compact('event'));
        $startDate = $event->start_at->format('m/d/Y h:i A');
        $endDate = $event->finish_at->format('m/d/Y h:i A');

        $result['fromTo'] = $startDate.' - '.$endDate;

        return view('event.edit', $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, UpdateEventRequest $request)
    {
        $event = Mvent::find($id);
        $sf = $request->get('start-finish');
        $dts = explode(' - ', $sf);
//        dd($dts[1]);
        $request['start_at'] = Carbon::createFromFormat('m/d/Y h:i A', $dts[0]);
        $request['finish_at'] = Carbon::createFromFormat('m/d/Y h:i A', $dts[1]);
        if($request->get('is_active')) {
            $request['is_active'] = 1;
        } else {
            $request['is_active'] = 0;
        }
//        Mvent::create($request->all());
        $event->update($request->all());
        return redirect('event');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function choose($id) {
        $data = Igm::getEventTemplateData();
        return view('event.choose', array_merge(['event_id' => $id],$data));
    }

    public function getBrandData() {

        $msg = new \stdClass;
        $msg->brandId = Session::get('brand_id');

        return json_encode($msg);

    }
    
    public function getNewMedia() {
        $this->getMedia();
    }

    public function updateNewMedia() {
        $this->getMedia(320);
    }

    public function getMedia($mediaGetLimit = 32) {

        Log::info("enter [getNewMedia]");
        // Settings

        $mediaCount = 32;
        $dayCount = 1;
        $mediaUpdateCount = 0;

        try {

            $events = Mvent::whereIsActive(true)->get();
            foreach($events as $event) {


                $startTime = Carbon::now();

                Instagram::setAccessToken(Igm::getWorkerAccessToken());

                $tagName = $event->tag;
                $startTime = $event->start_at;

                $expiredCount = 0;
                $mediaGetCount = 0;

                $mediaList = Instagram::getTagMedia($tagName, $mediaCount);
                $mediaUpdated = 0;

                //if(empty($mediaList->data)) continue;

                foreach ($mediaList->data as $media) {

                    //Event::fire(new Notification("--- media : " . $media->id));

                    if ($expiredCount > 2 || ($mediaGetCount >= $mediaGetLimit)) {
                        $continue = false;
                        break;
                    }

                    $createdTime = Carbon::createFromTimestamp($media->created_time, 'Asia/Bangkok');

                    if ($createdTime->lt($startTime)) {
                        $expiredCount++;
                        continue;
                    } else {
                        //echo($createdTime."</br>");
                        $findMedia = Media::find($media->id);
                        if (is_null($findMedia)) {
                            Media::createByMedia($media);

                            $fanId = $media->user->id;
                            if (is_null(Fan::find($fanId))) {
                                Fan::createByMedia($media->user);
                            }
                            $mediaUpdateCount++;
                            $mediaUpdated++;
                        }
                        if(is_null(EventMedia::whereMediaId($media->id)->whereEventId($event->id)->first())) {
                            //echo "{$media->id} - {$event->id}<br/>";
                            $evm = new EventMedia();
                            $evm->media_id = $media->id;
                            $evm->event_id = $event->id;
                            $evm->is_use =true;
                            $evm->save();
                        }
                        $mediaGetCount++;
                    }
                }
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

            //ErrorLog::create([
            //    'brand_id' => 0,
            //    'object_id' => 0,
            //    'media_id' => 0,
            //    'type' => $taskType,
            //    'message' => $exception->getMessage(),
            //]);
            //
            //Event::fire(new Notification('Update Tag Media Error : (' . $exception->getCode() . ') ' . $exception->getMessage() . $exception->getTraceAsString()));
        }
    }

}
