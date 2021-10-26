<?php

namespace GindowCloud\Containers\Authentication;

use GindowCloud\Kernel\Application;

class Captcha extends Application
{
    public function send($phone, $content = null)
    {
        return 200 == $this->httpPost('captcha', [
            'phone' => $phone,
            'content' => $content
        ])->json()->code;
    }

    public function check($phone, $captcha)
    {
        return 200 == $this->httpPost('captcha/check', [
            'phone' => $phone,
            'captcha' => $captcha
        ])->json()->code;
    }
}
