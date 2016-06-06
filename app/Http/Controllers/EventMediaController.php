<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\EventMedia;
use Carbon\Carbon;
use Input;
use Igm;


class EventMediaController extends Controller
{

    public function index()
    {
        $media = EventMedia::with('media')->orderBy('id','DESC')->get();
        $data = Igm::getViewerTemplateData();
        $data["media"] = $media;
        return view('media.index', $data);
    }

    public function create()
    {
        return json_encode(ViewGenerator::getFormSchema(
            array_merge(Customer::getFormData(), EventMedia::getFormData()), 'EventMedias.create'));
    }

    public function store()
    {
        $data = Input::all();
        $data = array_diff_key($data, array_flip(['id','_method','deleted_at','deleted_by','updated_by','created_by','updated_at','created_at']));
        $status = EventMedia::create($data);
        if($status === NULL) {
            return ['status'=>500];
        }
        return ['status'=>200];
    }

    public function show($id)
    {
        $media = EventMedia::whereEventId($id)->with('media')->orderBy('id','DESC')->get();
        $data = Igm::getViewerTemplateData();
        $data["media"] = $media;
        return view('media.index', $data);
    }

    public function edit($id)
    {
        $EventMedia = EventMedia::find($id);
        $customerId = $EventMedia->customer->id;
        return json_encode(ViewGenerator::getFormSchema(
            array_merge(Customer::getFormData($customerId), EventMedia::getFormData()), 'EventMedias.create', EventMedia::find($id)));
    }

    public function update($id)
    {
        $data = Input::all();
        $data = array_diff_key($data, array_flip(['id','_method','deleted_at','deleted_by','updated_by','created_by','updated_at','created_at']));
        $status = EventMedia::whereId($id)->update($data);
        if($status == 1) {
            return ['status'=>200];
        }
        return ['status'=>500];
    }

    public function destroy($id)
    {
        $EventMedia = EventMedia::find($id);
        if (is_null($EventMedia)) {
            EventMedia::withTrashed()->whereId($id)->first()->restore();
        }else{
            $EventMedia->delete();
        }
    }

}