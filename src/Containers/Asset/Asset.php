<?php

namespace GindowCloud\Containers\Asset;

use GindowCloud\Kernel\Application;

class Asset extends Application
{
    public function upload($file)
    {
        $json = $this->uploadJson('assets', [
            ['name' => 'type_id', 'contents' => 'IMAGE'],
            ['name' => 'file', 'contents' => fopen($file, 'r')],
        ]);
        return 200 == $json->code ? $json->data->url : null;
    }
}
