<?php namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class EventMedia extends Model {

    protected $table = 'media_tag';

    protected $fillable = array(
        'media_id',
        'fan_id',
        'tag_id',
        'comment_count',
        'like_count',
    );

    public function media()
    {
        return $this->belongsTo('App\Media');
    }

    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }

    public function fan()
    {
        return $this->belongsTo('App\Fan');
    }

    public function scopeShouldUpdate($query)
    {
        $query->whereExists(function($query)
        {
            $query->select(DB::raw(1))
                ->from('media')
                ->whereRaw("media.id = media_tag.media_id AND media.checked_at <= '".Carbon::now()->addHours(-1)."'")
                ->where('created_time' ,'>', Carbon::now()->addDays(-10)->timestamp);
        });
    }
}