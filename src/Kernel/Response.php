<?php

namespace GindowCloud\Kernel;

use Illuminate\Support\Arr;

class Response
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function json()
    {
        return json_decode($this->content);
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
