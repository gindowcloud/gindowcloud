<?php

namespace GindowCloud\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gindow.sms';
    }
}
