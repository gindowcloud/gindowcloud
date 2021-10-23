<?php

namespace GindowCloud\Facades;

use Illuminate\Support\Facades\Facade;

class Category extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gindow.category';
    }
}
