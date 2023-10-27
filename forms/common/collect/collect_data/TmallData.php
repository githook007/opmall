<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/5/18
 * Time: 10:46
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\collect\collect_data;


use app\forms\common\collect\collect_api\Tmall;

/**
 * Class TmallData
 * @package app\forms\common\collect\collect_data
 */
class TmallData extends AliData
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->api = new Tmall();
    }

    public function getItemId($url)
    {
        $id = $this->pregSubstr('/(\?id=|&id=)/', '/&/', $url);
        if (empty($id)) {
            throw new \Exception($url . '链接错误，没有包含商品id');
        }
        $itemId = $id[0];
        return $itemId;
    }
}
