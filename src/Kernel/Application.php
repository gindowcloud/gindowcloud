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
     * @param array $options
     * @param bool $auth
     * @return string
     * @throws GuzzleException
     */
    public function request($method, $url, $options = [], $auth = false)
    {
        if ($auth & !$this->token) {
            $this->token = $this->getToken();
        }
        try {
            $url = $this->url . (substr($url, 0, 1) == '/' ? '' : '/') . $url;
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

    public function getJson($url, $query = [], $auth = true)
    {
        return json_decode($this->request('GET', $url, ['query' => $query], $auth));
    }

    public function postJson($url, $formParams = [], $auth = true)
    {
        return json_decode($this->request('POST', $url, ['form_params' => $formParams], $auth));
    }

    public function patchJson($url, $formParams = [], $auth = true)
    {
        return json_decode($this->request('PATCH', $url, ['form_params' => $formParams], $auth));
    }

    public function deleteJson($url, $formParams = [], $auth = true)
    {
        return json_decode($this->request('DELETE', $url, ['form_params' => $formParams], $auth));
    }

    public function uploadJson($url, $multipart = [], $auth = true)
    {
        return json_decode($this->request('POST', $url, ['multipart' => $multipart], $auth));
    }
}
