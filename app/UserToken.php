<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model {

    protected $table = 'user_token';

    protected $fillable = array(
        'user_id',
        'fan_id',
        'token',
    );

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function fan()
    {
        return $this->hasOne('App\Fan','id','fan_id');
    }
}