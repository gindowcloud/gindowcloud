<?php

namespace GindowCloud\Kernel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class Application
{
    protected $url;
    protected $clientId;
    protected $clientSecret;
    protected $token;

    protected $cacheSecond = 24 * 60 * 60; // 缓存时间（秒）
    private $cacheToken = 'gindow:access_token';

    public function __construct()
    {
        $this->url = config('gindow.url');
        $this->clientId = config('gindow.client_id');
        $this->clientSecret = config('gindow.client_secret');
    }

    private function getToken()
    {
        return Cache::remember($this->cacheToken, $this->cacheSecond, function () {
            $json = $this->postJson('/token', [
                'app_id' => $this->clientId,
                'secret' => $this->clientSecret,
            ], false);
            return $json->data->access_token;
        });
    }

    /**
     * 网络请求 Request
     * @param $method
     * @param $url
     * @param array $query
     * @param array $params
     * @param bool $auth
     * @return string
     * @throws GuzzleException
     */
    public function request($method, $url, $query = [], $params = [], $auth = false)
    {
        if ($auth & !$this->token) {
            $this->token = $this->getToken();
        }
        try {
            $url = $this->url . (substr($url, 0, 1) == '/' ? '' : '/') . $url;
            $options = ['query' => $query, 'form_params' => $params];
            if ($auth) {
                $options['headers'] = [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                ];
            }
            $ret = (new Client)->request($method, $url, $options);
            return $ret->getBody()->getContents();
        } catch (\Exception $e) {
            if (401 == $e->getCode()) {
                Cache::forget($this->cacheToken);
            }
            return $e->getResponse()->getBody()->getContents();
        }
    }

    /**
     * 网络请求
     * @param $url
     * @param array $query
     * @param bool $auth
     * @return string
     * @throws GuzzleException
     */
    public function get($url, $query = [], $auth = true)
    {
        return $this->request('GET', $url, $query, [], $auth);
    }

    public function post($url, $params = [], $auth = true)
    {
        return $this->request('POST', $url, [], $params, $auth);
    }

    public function patch($url, $params = [], $auth = true)
    {
        return $this->request('PATCH', $url, [], $params, $auth);
    }

    public function delete($url, $params = [], $auth = true)
    {
        return $this->request('DELETE', $url, [], $params, $auth);
    }

    /**
     * 转化输出 JSON
     * @param $url
     * @param array $query
     * @param bool $auth
     * @return mixed
     * @throws GuzzleException
     */
    public function getJson($url, $query = [], $auth = true)
    {
        return json_decode($this->get($url, $query, $auth));
    }

    public function postJson($url, $params = [], $auth = true)
    {
        return json_decode($this->post($url, $params, $auth));
    }

    public function patchJson($url, $params = [], $auth = true)
    {
        return json_decode($this->patch($url, $params, $auth));
    }

    public function deleteJson($url, $params = [], $auth = true)
    {
        return json_decode($this->delete($url, $params, $auth));
    }
}
