<?php namespace App\Http\Controllers;

use App\ActiveTime;
use App\Events\Notification;
use App\FanStat;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MessageLog;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\CreateBrandRequest;
use App\Handlers\Events\NotificationHandler;
use Event;
use Igm;
use Auth;
use Session;
use Redis;
use Log;

use App\Brand;

class BrandController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('instagram');
    }

	public function index()
	{
        return redirect('brand/'.Session::get('brand_id').'/edit');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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

	public function edit(Brand $brand)
	{
        $data = Igm::getViewerTemplateData();
        $result = array_merge($data, compact('brand'));
        $user = Auth::getUser();
        $result['username'] = $user->name;
        $result['brandname'] = $user->brand->fan->user_name;
//        dd($result['brand']->tags->lists('name'));
        $result['brand']->tags = implode(',',$result['brand']->tags->lists('name'));
        return view('settings.brand.edit', $result);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Brand $brand, CreateBrandRequest $request)
	{
        $tags = explode(',', $request->get('tags'));
        $sync = [];
        foreach ($tags as $tag) {
            $first = Tag::whereName($tag)->first();
            if(is_null($first)) {
                $result = Tag::create([
                    'name' => $tag
                ]);
                $sync[] = $result->id;
            } else {
                $sync[] = $first->id;
            }
        }


        $brand->update($request->all());
        $brand->tags()->sync($sync);

        return redirect('brand');
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

    public function getBrandData() {

        $msg = new \stdClass;
        $msg->brandId = Session::get('brand_id');

        return json_encode($msg);

    }

    public function getActivity() {

        if(Session::get('brand_id') == 1) {
            $activity = MessageLog::latest()->where('type', '<', 20)->take(150)->get();
            $task = MessageLog::latest()->where('type', '>=', 20)->take(20)->get();
            $msg = $activity->merge($task);
        } else {
            $activity = MessageLog::latest()->where('type', '<', 20)->take(30)->whereBrandId(Session::get('brand_id'))->get();
            $task = MessageLog::latest()->where('type', '>=', 20)->take(15)->whereBrandId(Session::get('brand_id'))->get();
            $msg = $activity->merge($task);
        }
        //dd($msg);
        return json_encode($msg);

    }

    public function getActiveTime() {

        if(Session::get('brand_id') == 1) {
            $msg = ActiveTime::whereBrandId(Session::get('brand_id'))->first();
        } else {
            $msg = ActiveTime::whereBrandId(Session::get('brand_id'))->first();
        }
        //dd($msg);
        return json_encode($msg);

    }

    public function getStatFollow() {

        $brand = Brand::find(Session::get('brand_id'));
        $fanId = $brand->fan_id;
        if(Session::get('brand_id') == 1) {
            $msg = FanStat::whereFanId($fanId)->orderBy('day')->get();
        } else {
            $msg = FanStat::whereFanId($fanId)->orderBy('day')->get();
        }
        //dd($msg);
        return json_encode($msg);

    }

}
