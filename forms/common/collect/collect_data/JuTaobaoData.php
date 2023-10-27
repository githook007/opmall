<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/6/17
 * Time: 11:11
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\collect\collect_data;


use app\forms\common\collect\collect_api\Taobao;

/**
 * Class JuTaobaoData
 * @package app\forms\common\collect\collect_data
 */
class JuTaobaoData extends AliData
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->api = new Taobao();
    }

    public function getItemId($url)
    {
        $id = $this->pregSubstr('/(\?item_id=|&item_id=)/', '/&/', $url);
        if (empty($id)) {
            throw new \Exception($url . '链接错误，没有包含商品id');
        }
        $itemId = $id[0];
        return $itemId;
    }
}
