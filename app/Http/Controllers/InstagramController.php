<?php namespace App\Http\Controllers;

use App\ErrorLog;
use App\Fan;
use App\Media;
use App\UserToken;
use Bus;
use Carbon\Carbon;
use DB;
use Event;
use Queue;
use Request;
use Instagram;
use Igm;
use Session;
use Redirect;
use Auth;
use Log;
use URL;

class InstagramController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
    {
        Log::notice('dfdf');
        dd('fin');

	}

    public function callback()
    {
        Log::info('- auth instagram controller -');
        $code = Request::get('code');
        if($code) {
            $auth = Instagram::getOAuthToken($code);
            if(isset($auth->access_token)) {
                Session::set('access_token', $auth);

                $userToken = UserToken::updateOrCreate([
                    'token' => $auth->access_token,
                    'fan_id' => $auth->user->id,
                    'user_id' => Auth::getUser()->id
                ]);

                $fan = Fan::updateOrCreate([
                    'id' => $auth->user->id,
                    'user_name' => $auth->user->username,
                    'full_name' => $auth->user->full_name,
                    'profile_picture' => $auth->user->profile_picture,
                    'bio' => $auth->user->bio,
                    'website' => $auth->user->website
                ]);

                Session::set('brand_id', $userToken->id);
                Session::set('brand_name', $userToken->fan->user_name);
                Session::set('profile_picture', $userToken->fan->profile_picture);

                Log::info('[Login] success getOAuthToken.'.$auth->access_token.', id: '.$auth->user->id);

                return Redirect::to('dashboard');
            } else {
                Log::info('[Login] error getOAuthToken. Redirected.');
                return Redirect::to(Instagram::getLoginUrl(array('basic','relationships','likes','comments')));
            }

        } else {
            return 'An error occurred: ' . Request::get('error_description');
        }
    }
}
