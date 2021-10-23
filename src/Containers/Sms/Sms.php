<?php

namespace GindowCloud\Containers\Sms;

use GindowCloud\Kernel\Application;

class Sms extends Application
{
    public function send($phone, $content, $template = null, $data = null)
    {
        return $this->postJson('/sms', [
            'phone' => $phone,
            'content' => $content,
            'template' => $template,
            'data' => $data,
        ]);
    }
}
