<?php namespace App\Http\Controllers;

use Igm;
use URL;

class DashboardController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public $data;

	public function __construct()
	{

		$this->middleware('auth');
		$this->middleware('instagram');
		// $this->data = $data;
		
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = Igm::getDashboardTemplateData();
        $segments = explode('/', URL::to('/') );
        $data['baseurl'] = $segments[2];

        return view('dashboard', $data);
	}

}
