<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable DevBoost
    |--------------------------------------------------------------------------
    |
    | This option determines whether DevBoost is enabled or disabled.
    | Set this to `false` to disable all DevBoost functionality.
    |
    */

    'enabled' => env('DEVBOOST_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default Timeout
    |--------------------------------------------------------------------------
    |
    | This option sets the default timeout (in seconds) for DevBoost operations.
    | You can override this value in your `.env` file using the `DEVBOOST_TIMEOUT` key.
    |
    */

    'timeout' => env('DEVBOOST_TIMEOUT', 60),

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | This option enables or disables debug mode for DevBoost.
    | When enabled, additional debug information will be logged.
    |
    */

    'debug' => env('DEVBOOST_DEBUG', false),

];
