<?php

namespace app\plugins\erp\forms\common\api;

trait Auth
{
    /**
     * 生成授权链接
     * @param $state
     * @fun createUrl
     */
    public function createUrl($state = ''): string
    {
        $data = [
            'app_key' => $this->app_key,
            'timestamp' => time(),
            'charset' => $this->charset,
            'state' => $state,
        ];
        $sign = $this->getSign($data);
        return $this->getAuthUrl .
            '?app_key=' . $this->app_key .
            '&timestamp=' . $data['timestamp'] .
            '&charset=' . $this->charset .
            '&sign=' . $sign .
            '&state=' . $state;
    }


    /**
     * 获取访问令牌
     * @fun getAccessToken
     * @param $code
     * @return array
     */
    public function getAccessToken($code): array
    {
        $data = [
            'app_key' => $this->app_key,
            'timestamp' => time(),
            'grant_type' => 'authorization_code',
            'charset' => $this->charset,
            'code' => $code,
        ];
        $data['sign'] = $this->getSign($data);
        $res = $this->post(ServeHttp::ACCESS_TOKEN, $data);
        $this->updateData($res);
        return $res;
    }

    /**
     * 更新授权令牌
     * @fun refreshToken
     * @return array
     * @date 2022/8/20
     * @author 刘铭熙
     */
    public function refreshToken(): array
    {
        if(!$this->refresh_token){
            throw new \Exception('刷新令牌为空');
        }
        $data = [
            'app_key' => $this->app_key,
            'timestamp' => time(),
            'grant_type' => 'refresh_token',
            'charset' => $this->charset,
            'refresh_token' => $this->refresh_token,
            'scope' => 'all',
        ];

        $data['sign'] = $this->getSign($data);
        $res = $this->post(ServeHttp::REFRESH_TOKEN, $data);
        $this->updateData($res);
        return $res;
    }

    protected function updateData($tokenRes){
        if($tokenRes['code'] != 0){
            throw new \Exception($tokenRes['msg']);
        }
        $this->access_token = $tokenRes['data']['access_token'];
        $this->refresh_token = $tokenRes['data']['refresh_token'];
        $this->expires_in = time() + $tokenRes['data']['expires_in'] - 10;
    }
}