<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Instagram Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'main' => [
            'client_id' => '3348c2046cb547008f583cdf5f3021c6',
            'client_secret' => '869f6f037a794ba7b4ecfcd19c1edca5',
            'callback_url' => 'http://103.245.167.79/memoprint/public/instagram/callback'
        ],

        'alternative' => [
            'client_id' => '0799d475ec284d16b74f3b8bef5e3824',
            'client_secret' => '4737483fec404a8380425608d282f063',
            'callback_url' => 'http://103.245.167.79:3001/handleauth'
        ],

    ]

];
