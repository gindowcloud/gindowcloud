<?php

namespace GindowCloud\Facades;

use Illuminate\Support\Facades\Facade;

class Gindow extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gindow';
    }
}
