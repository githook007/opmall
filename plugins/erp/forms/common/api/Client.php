<?php

namespace app\plugins\erp\forms\common\api;

use GuzzleHttp\Client as Clients;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use yii\helpers\Json;

trait Client
{
    public function post($url, $params):array
    {
        $data = [
            'app_key' => $this->app_key,
            'access_token' => $this->access_token,
            'timestamp' => time(),
            'charset' => $this->charset,
            'version' => $this->version,
        ];
        $data['biz'] = !empty($params) ? Json::encode($params) : '{}';
        $data['sign'] = $this->getSign($data);
        return $this->sendRequest($url, $data);
    }

    private function sendRequest($url, array $options):array
    {
        $client = new Clients([
            'base_uri' => $this->getUrl(),
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
            ]
        ]);

        $request = $client->postAsync($url, [
            'form_params' => $options
        ]);
        $promise = $request->then(function (ResponseInterface $request) {
            $contents = $request->getBody()->getContents();
            return Json::decode($contents);
        }, function (RequestException $exception) {
            return Json::decode(Json::encode($exception));
        });
        return $promise->wait();
    }
}