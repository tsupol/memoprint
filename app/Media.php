<?php namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Log;

class Media extends Model {

    public $incrementing = false;
    //
    protected $fillable = array(
        'id',
        'media_type',
        'img_low',
        'img_high',
        'vid_low',
        'vid_high',
        'img_width',
        'img_height',
        'vid_width',
        'vid_height',
        'user_id',
        'like_count',
        'tags',
        'caption',
        'created_time',
        'lat',
        'lng',
    );

    public function scopeShouldUpdate($query)
    {
        $query->where('checked_at', '<=', Carbon::now()->addHours(-4))
            ->whereExists(function($query)
            {
                $query->select(DB::raw('media_id'))
                    ->from('media_tag')
                    ->whereRaw('media_tag.media_id = media.id');
            });
    }

    public static function createByMedia($media) {

        $result = Media::find($media->id);
        if(is_null($result)) {
            $isVideo = isset($media->videos);
            $val = [
                'id' => $media->id,
                'media_type' => $media->type,
                'img_low' => $media->images->low_resolution->url,
                'img_high' => $media->images->standard_resolution->url,
                'img_width' => $media->images->standard_resolution->width,
                'img_height' => $media->images->standard_resolution->height,

                'vid_low' => $isVideo ? $media->videos->low_resolution->url : '',
                'vid_high' => $isVideo ? $media->videos->standard_resolution->url : '',
                'vid_width' => $isVideo ? $media->videos->standard_resolution->width : 0,
                'vid_height' => $isVideo ? $media->videos->standard_resolution->height : 0,

                'user_id' => $media->user->id,
                'like_count' => $media->likes->count,
                'tags' => implode(",", $media->tags),
                'caption' => empty($media->caption->text) ? "" : $media->caption->text,
                'created_time' => $media->created_time,
                'lat' => isset($media->location->latitude) ? $media->location->latitude : 0,
                'lng' => isset($media->location->longitude) ? $media->location->longitude : 0,
            ];
//            Log::info("[----] type: {$media->type} vid : {$media->videos->low_resolution->url}");
//            if($media->type == 'video') {
//                Log::info("[----vid] type: {$media->type} vid : {$media->videos->low_resolution->url}");
//
//            }
            return static::create($val);
        } else {
            return $result;
        }
    }

    public static function updateByMedia($media) {

        $result = Media::find($media->id);
        if(is_null($result)) {
            return static::create([
                'id' => $media->id,
                'media_type' => $media->type,
                'img_low' => $media->images->low_resolution->url,
                'img_high' => $media->images->standard_resolution->url,
                'user_id' => $media->user->id,
                'like_count' => $media->likes->count,
                'tags' => implode(",", $media->tags),
                'caption' => empty($media->caption->text) ? "" : $media->caption->text,
                'created_time' => $media->created_time,
                'lat' => isset($media->location->latitude) ? $media->location->latitude : 0,
                'lng' => isset($media->location->longitude) ? $media->location->longitude : 0,
            ]);
        } else {
            return $result->update([
                'id' => $media->id,
                'media_type' => $media->type,
                'img_low' => $media->images->low_resolution->url,
                'img_high' => $media->images->standard_resolution->url,
                'user_id' => $media->user->id,
                'like_count' => $media->likes->count,
                'tags' => implode(",", $media->tags),
                'caption' => empty($media->caption->text) ? "" : $media->caption->text,
                'created_time' => $media->created_time,
                'lat' => isset($media->location->latitude) ? $media->location->latitude : 0,
                'lng' => isset($media->location->longitude) ? $media->location->longitude : 0,
            ]);
        }
    }

}
