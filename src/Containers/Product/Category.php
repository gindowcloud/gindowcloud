<?php

namespace GindowCloud\Containers\Product;

use GindowCloud\Kernel\Application;
use GindowCloud\Kernel\Traits\ResourceTrait;

class Category extends Application
{
    use ResourceTrait;

    protected $resource = 'categories';
}
