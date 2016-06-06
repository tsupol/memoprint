<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Mvent extends Model {

    protected $table = 'events';

    protected $dates = ['start_at', 'finish_at'];

    protected $fillable = array(
        'name',
        'tag',
        'tag2',
        'fan_id',
        'tag_mode',
        'mode',
        'is_active',
        'start_at',
        'finish_at',
    );

    public function fan()
    {
        return $this->hasOne('App\Fan','id','fan_id');
    }

}