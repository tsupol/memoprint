<?php namespace App\Igm\Facades;

use Illuminate\Support\Facades\Facade;

class Igm extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'igm';
    }

}