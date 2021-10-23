<?php

namespace GindowCloud\Containers\Settings;

use GindowCloud\Kernel\Application;
use Illuminate\Support\Facades\Cache;

class Settings extends Application
{
    /**
     * 全部参数
     * @return mixed
     */
    public function all()
    {
        $ret = [];
        $json = $this->getJson('/settings', ['size' => 100]);
        foreach ($json->data as $item) {
            $ret[$item->key] = $item->value;
        }
        return $ret;
    }

    /**
     * 读取参数
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = Cache::remember($this->cacheKey($key), $this->cacheSecond, function () use ($key) {
            $json = $this->getJson('/config/' . $key);
            return $json->data ? $json->data->value ?? null : null;
        });
        return $value ?? $default;
    }

    /**
     * 设置参数
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $this->postJson('/config/' . $key, ['value' => $value]);
        return $this->cacheForget($key);
    }
    /**
     * 缓存名称
     * @param $key
     * @return string
     */
    private function cacheKey($key)
    {
        return "settings:" . $key;
    }

    private function cacheForget($key)
    {
        return Cache::forget($this->cacheKey($key));
    }
}
