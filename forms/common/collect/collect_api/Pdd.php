<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/5/18
 * Time: 15:51
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\collect\collect_api;


class Pdd extends CollectApi
{
    public function getData($itemId)
    {
        $url = 'https://api03.6bqb.com/pdd/detail?';
        $result = $this->httpGet($url . http_build_query(['apikey' => $this->api_key, 'itemid' => $itemId]));
        if (!$result || empty($result)) {
            throw new \Exception('拼多多--采集失败');
        }
        return $result;
    }
}
