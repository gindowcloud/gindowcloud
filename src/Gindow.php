<?php

namespace GindowCloud;

use Illuminate\Session\SessionManager;
use Illuminate\Config\Repository;

class Gindow
{
    protected $session;
    protected $config;

    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    public function __call($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }

    public static function make($name, array $config = [])
    {
        $application = "\\GindowCloud\\" . ucwords($name) . "\\Application";
        return new $application($config);
    }
}
