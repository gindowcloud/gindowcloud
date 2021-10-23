<?php

namespace GindowCloud\Containers\Authentication;

use GindowCloud\Kernel\Application;

class Captcha extends Application
{
    public function send($phone, $content = null)
    {
        return $this->post('/captcha', ['phone' => $phone, 'content' => $content]);
    }

    public function check($phone, $captcha)
    {
        return 200 == $this->postJson('/captcha/check', ['phone' => $phone, 'captcha' => $captcha])->code;
    }
}
