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
        $data = [];
        $json = $this->httpGet('settings', [
            'size' => 100
        ])->json();
        if ($json->code == 200) {
            foreach ($json->data as $item) {
                $data[$item->key] = $item->value;
            }
        }
        return $data;
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
            $json = $this->httpGet('config/' . $key)->json();
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
        $this->httpPost('config/' . $key, ['value' => $value]);
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
