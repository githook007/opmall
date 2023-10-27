<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/6
 * Time: 3:59 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\wxapp\models\shop;

use app\helpers\CurlHelper;

class SaleService extends BaseService
{
    public function getClient()
    {
        return CurlHelper::getInstance()->setPostType(CurlHelper::BODY);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/framework/ministore/minishopopencomponent2/API/aftersale/add.html
     * 创建售后
     */
    public function add($args)
    {
        $api = "https://api.weixin.qq.com/shop/aftersale/add?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], [
            'out_order_id' => $args['out_order_id'],
            'out_aftersale_id' => $args['out_aftersale_id'],
            'openid' => $args['openid'],
            'type' => $args['type'],
            'create_time' => $args['create_time'],
            'status' => $args['status'],
            'finish_all_aftersale' => $args['finish_all_aftersale'],
            'product_infos' => $args['product_infos']
        ]);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/aftersale/add_new.html
     * 创建售后
     */
    public function addNew($args)
    {
        if (!isset($args['order_id']) && !isset($args['out_order_id'])) {
            throw new \Exception('缺少订单id');
        }
        $api = "https://api.weixin.qq.com/shop/ecaftersale/add?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $args);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/framework/ministore/minishopopencomponent2/API/aftersale/update.html
     * 只能更新售后状态
     */
    public function update($args)
    {
        $params = [];
        if (isset($args['order_id'])) {
            $params['order_id'] = $args['order_id'];
        } elseif (isset($args['out_order_id'])) {
            $params['out_order_id'] = $args['out_order_id'];
        } else {
            throw new \Exception('缺少订单id');
        }
        $params['openid'] = $args['openid'];
        $params['out_aftersale_id'] = $args['out_aftersale_id'];
        $params['status'] = $args['status'];
        $params['finish_all_aftersale'] = $args['finish_all_aftersale'];
        $api = "https://api.weixin.qq.com/shop/aftersale/update?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $params);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/aftersale/aftersale_update.html
     * 更新售后
     */
    public function updateNew($args)
    {
        if (!isset($args['order_id']) && !isset($args['out_order_id'])) {
            throw new \Exception('缺少订单id');
        }
        $api = "https://api.weixin.qq.com/shop/ecaftersale/update?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $args);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/aftersale/get_new.html
     * 获取订单下售后单
     */
    public function get($args)
    {
        $params = [];
        if (isset($args['aftersale_id'])) {
            $params['aftersale_id'] = $args['aftersale_id'];
        } elseif (isset($args['out_aftersale_id'])) {
            $params['out_aftersale_id'] = $args['out_aftersale_id'];
        } else {
            throw new \Exception('缺少订单id');
        }
        $api = "https://api.weixin.qq.com/shop/ecaftersale/get?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $params);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/aftersale/cancel.html
     * 用户取消售后单
     */
    public function cancel($args)
    {
        $params = [];
        if (isset($args['aftersale_id'])) {
            $params['aftersale_id'] = $args['aftersale_id'];
        } elseif (isset($args['out_aftersale_id'])) {
            $params['out_aftersale_id'] = $args['out_aftersale_id'];
        } else {
            throw new \Exception('缺少单号');
        }
        $params['openid'] = $args['openid'];
        $api = "https://api.weixin.qq.com/shop/ecaftersale/cancel?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $params);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/aftersale/acceptrefund.html
     * 同意退款
     */
    public function acceptRefund($args)
    {
        $params = [];
        if (isset($args['aftersale_id'])) {
            $params['aftersale_id'] = $args['aftersale_id'];
        } elseif (isset($args['out_aftersale_id'])) {
            $params['out_aftersale_id'] = $args['out_aftersale_id'];
        } else {
            throw new \Exception('缺少单号');
        }
        $api = "https://api.weixin.qq.com/shop/ecaftersale/acceptrefund?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $params);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/aftersale/acceptreturn.html
     * 同意退货
     */
    public function acceptReturn($args)
    {
        $params = [];
        if (isset($args['aftersale_id'])) {
            $params['aftersale_id'] = $args['aftersale_id'];
        } elseif (isset($args['out_aftersale_id'])) {
            $params['out_aftersale_id'] = $args['out_aftersale_id'];
        } else {
            throw new \Exception('缺少单号');
        }
        $params['address_info'] = $args['address_info'];
        $api = "https://api.weixin.qq.com/shop/ecaftersale/acceptreturn?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $params);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/aftersale/reject.html
     * 拒绝售后
     */
    public function reject($args)
    {
        $params = [];
        if (isset($args['aftersale_id'])) {
            $params['aftersale_id'] = $args['aftersale_id'];
        } elseif (isset($args['out_aftersale_id'])) {
            $params['out_aftersale_id'] = $args['out_aftersale_id'];
        } else {
            throw new \Exception('缺少单号');
        }
        $api = "https://api.weixin.qq.com/shop/ecaftersale/reject?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $params);
        return $this->getResult($res);
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/aftersale/uploadreturninfo.html
     * 用户上传物流信息
     */
    public function uploadReturnInfo($args)
    {
        $api = "https://api.weixin.qq.com/shop/ecaftersale/uploadreturninfo?access_token={$this->accessToken}";
        $res = $this->getClient()->httpPost($api, [], $args);
        return $this->getResult($res);
    }
}