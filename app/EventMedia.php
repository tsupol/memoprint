<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EventMedia extends Model {

    protected $table = 'event_media';

    protected $fillable = array(
        'event_id',
        'media_id',
        'is_use',
    );

    public function media()
    {
        return $this->belongsTo('App\Media');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

}