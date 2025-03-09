<?php

namespace BadrQaba\DevBoost\Facades;

use Illuminate\Support\Facades\Facade;

class DevBoost extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'devboost';
    }
}
