<?php namespace App\Http\Middleware;

use Closure;
use Instagram;

use Auth;
use Session;
use Log;

class AuthInstagram {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('- auth instagram -');
        $token = Session::get('access_token');
        if($token === null) {
            $brand = Auth::getUser()->userToken;
//            dd($brand);
            if($brand) {
                Session::set('access_token', $brand->token);
                $fan = $brand->fan;
                Instagram::setAccessToken($brand->token);
                Session::set('brand_id', $brand->id);
                Session::set('brand_name', $fan->user_name);
                Session::set('profile_picture', $fan->profile_picture);
                Log::info('[Login] get from database. token: '.$brand->token.', id: '.$fan->id);
            } else {
                Log::info('[Login] brand is undefined. Redirected.');
                return redirect(Instagram::getLoginUrl(array('basic','relationships','likes','comments')));
            }
        } else {
            /* if(is_string($token)) {
                Log::info('[Login] session is set. token: '.$token.', id: '.Session::get('insta_user')->id);
            } else {
                Log::info('[Login] session is set. token: '.$token->access_token.', id: '.Session::get('insta_user')->id);
            }
            Log::info('[555] token: '.Instagram::getAccessToken().', id: '.Session::get('insta_user')->id); */
            Instagram::setAccessToken($token);
        }
        return $next($request);
    }

}
