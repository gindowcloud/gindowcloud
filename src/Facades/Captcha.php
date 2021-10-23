<?php

namespace GindowCloud\Facades;

use Illuminate\Support\Facades\Facade;

class Captcha extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gindow.captcha';
    }
}
