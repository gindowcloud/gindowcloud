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
            $json = $this->httpPost('/token', [
                'app_id' => $this->clientId,
                'secret' => $this->clientSecret,
            ], false)->json();
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
     */
    protected function request($method, $url, $options = [], $auth = false)
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
            $response = (new Client)->request($method, $url, $options);
            return new Response($response->getBody());
        } catch (GuzzleException $e) {
            if (401 == $e->getCode()) {
                Cache::forget($this->cacheToken);
            }
            return new Response($e->getResponse()->getBody());
        }
    }

    protected function httpGet($url, $query = [], $auth = true)
    {
        return $this->request('GET', $url, ['query' => $query], $auth);
    }

    protected function httpPost($url, $formParams = [], $auth = true)
    {
        return $this->request('POST', $url, ['form_params' => $formParams], $auth);
    }

    protected function httpPatch($url, $formParams = [], $auth = true)
    {
        return $this->request('PATCH', $url, ['form_params' => $formParams], $auth);
    }

    protected function httpDelete($url, $formParams = [], $auth = true)
    {
        return $this->request('DELETE', $url, ['form_params' => $formParams], $auth);
    }

    protected function httpUpload($url, $multipart = [], $auth = true)
    {
        return $this->request('POST', $url, ['multipart' => $multipart], $auth);
    }
}
