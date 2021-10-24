<?php

namespace GindowCloud;

use GindowCloud\Containers\Asset\Asset;
use GindowCloud\Containers\Authentication\Captcha;
use GindowCloud\Containers\Product\Category;
use GindowCloud\Containers\Settings\Settings;
use GindowCloud\Containers\Sms\Sms;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    protected $defer = true;
    protected $classes = [
        'gindow' => Gindow::class,
        'gindow.settings' => Settings::class,
        'gindow.sms' => Sms::class,
        'gindow.category' => Category::class,
        'gindow.captcha' => Captcha::class,
        'gindow.asset' => Asset::class,
    ];

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/gindow.php' => config_path('gindow.php'),
        ]);
    }

    /**
     * 注册服务
     */
    public function register()
    {
        foreach ($this->classes as $name => $class) {
            $this->app->singleton($name, function ($app) use ($class) {
                return new $class($app['session'], $app['config']);
            });
        }
    }

    /**
     * 延迟加载
     * @return array|string[]
     */
    public function provides()
    {
        return collect($this->classes)->keys()->toArray();
    }
}
