<?php

namespace app\plugins\erp\forms\common\api;

trait Util
{
    /**
     * 生成签名
     * @param array $data       参与签名数组
     * @return string|null
     */
    public function getSign(array $data): ?string
    {
        if (!$data) {
            return null;
        }
        ksort($data);
        $resultStr = "";
        foreach ($data as $key => $val) {
            if ($key != null && $key != "" && $key != "sign") {
                $resultStr = $resultStr . $key . $val;
            }
        }
        $resultStr = $this->app_secret . $resultStr;
        return bin2hex(md5($resultStr, true));
    }
}