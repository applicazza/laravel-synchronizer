<?php

namespace Applicazza\LaravelSynchronizer\Facades;

use Illuminate\Support\Facades\Facade;

class Synchronizer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'synchronizer';
    }
}