<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/5/18
 * Time: 10:51
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\collect\collect_api;


class AppTaobao extends CollectApi
{
    public function getData($itemId)
    {
        $url = 'https://api03.6bqb.com/app/taobao/detail?';
        $result = $this->httpGet($url . http_build_query(['apikey' => $this->api_key, 'itemId' => $itemId]));
        if (!$result || empty($result)) {
            throw new \Exception('app淘宝--采集失败');
        }
        return $result;
    }
}
