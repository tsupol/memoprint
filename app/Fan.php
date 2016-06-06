<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Fan extends Model {

    public $incrementing = false;

    protected $fillable = array(
        'id',
        'user_name',
        'full_name',
        'profile_picture',
        'bio',
        'website',
        'count_media',
        'count_following',
        'count_follower',
        'checked_at'
    );

    public static function createByMedia($media, $isPrivate = Null)
    {
        $fan = static::find($media->id);
        if(is_null($fan)) {
            return static::create([
                'id' => $media->id,
                'user_name' => $media->username,
                'full_name' => $media->full_name,
                'profile_picture' => $media->profile_picture,
                'is_private' => $isPrivate,
            ]);
        } else {
            return $fan;
        }
    }

    public function scopeShouldUpdate($query)
    {
        $query->where('checked_at', '<=', Carbon::now()->addHours(-4));
    }

    public static function createFan($user)
    {
        if(is_null(static::find($user->id))) {
            static::create([
                'id' => $user->id,
                'user_name' => $user->username,
                'full_name' => $user->full_name,
                'profile_picture' => $user->profile_picture,
                'bio' => $user->bio,
                'website' => $user->website,
                'count_media' => $user->counts->media,
                'count_following' => $user->counts->followed_by,
                'count_follower' => $user->counts->follows,
                'is_private' => false,
                'checked_at' => Carbon::now(),
            ]);
        }
    }
}
