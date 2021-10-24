<?php

namespace GindowCloud\Containers\Authentication;

use GindowCloud\Kernel\Application;

class Captcha extends Application
{
    public function send($phone, $content = null)
    {
        $json = $this->postJson('captcha', [
            'phone' => $phone,
            'content' => $content
        ]);
        return 200 == $json->code;
    }

    public function check($phone, $captcha)
    {
        $json = $this->postJson('captcha/check', [
            'phone' => $phone,
            'captcha' => $captcha
        ]);
        return 200 == $json->code;
    }
}
