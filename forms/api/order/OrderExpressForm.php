<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\api\order;

use app\bootstrap\express\Kuaidiniao;
use app\bootstrap\response\ApiCode;
use app\models\Mall;
use app\models\Model;
use app\models\Order;
use app\models\OrderDetailExpress;

class OrderExpressForm extends Model
{
    public $mobile; // 手机号，用于顺丰查信息
    public $express;
    public $express_no;
    public $customer_name;//京东物流特殊要求字段，商家编码

    public function rules()
    {
        return [
            [['customer_name', 'mobile'], 'string'],
            [['express', 'express_no'], 'required'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if ($this->customer_name === 'undefined') $this->customer_name = null;
        try {
            $expressData = $this->getExpressData($this->express, $this->express_no);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => 'success',
                'data' => [
                    'express' => $expressData,
                    'order' => [
                        'express' => $this->express,
                        'express_no' => $this->express_no,
                    ],
                ]
            ];
        } catch (\app\bootstrap\express\exception\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'data' => [
                    'express' => null,
                    'order' => [
                        'express' => $this->express,
                        'express_no' => $this->express_no,
                    ],
                ]
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => $exception->getMessage(),
                'data' => [
                    'express' => null,
                    'order' => [
                        'express' => $this->express,
                        'express_no' => $this->express_no,
                    ],
                ]
            ];
        }
    }

    private function transExpressName($name)
    {
        $staticNameList = [
            '百世快运',
            '京东快运',
        ];
        if (!$name) {
            return false;
        }
        if (in_array($name, $staticNameList)) {
            return $name;
        }


        $name_map_list = [
            '邮政快递包裹' => '邮政',
            '邮政包裹信件' => '邮政',
        ];
        if (isset($name_map_list[$name])) {
            $name = $name_map_list[$name];
        }

        $append_list = [
            '快递',
            '快运',
            '物流',
            '速运',
            '速递',
        ];
        foreach ($append_list as $append) {
            $name = str_replace($append, '', $name);
        }

        return $name;
    }

    private function getKuaidiniaoConfig()
    {
        $select_key = [
            'kdniao_mch_id',
            'kdniao_api_key',
            'express_aliapy_code',
            'express_select_type',
            'kd100_key',
            'kd100_customer',
            'kdniao_select_type',
        ];
        $mall = (new Mall())->getMallSetting($select_key);

        if (empty($mall)) {
            return array_fill(0, count($select_key), '');
        }
        return array_values($mall);
    }

    private function getExpressData($expressName, $expressNo)
    {
        $config = $this->getKuaidiniaoConfig();
        switch ($config[3]) {
            case 'wd':
                $config = ['code' => $config[2]];
                $wdExpress = \Yii::$app->ExpressTrack->create('Wd', $config);
                return $wdExpress->track($this->express_no, $this->express, $this->getMobile());
            case 'kd100':
                $config = ['code' => $config[4], 'customer' => $config[5]];
                $wdExpress = \Yii::$app->ExpressTrack->create('Kd100', $config);
                return $wdExpress->track($this->express_no, $this->express, $this->getMobile());
        }

        $statusMap = [
            -1 => '已揽件',
            0 => '已揽件',
            1 => '已发出',
            2 => '在途中',
            3 => '派件中',
            4 => '已签收',
            5 => '已自取',
            6 => '问题件',
            7 => '已退回',
            8 => '已退签',
        ];

        $last4Num = '';
        if(!$this->customer_name) {
            $mobile = $this->getMobile();
            if ($mobile && mb_strlen($mobile) > 4) {
                $last4Num = mb_substr($mobile, 0 - 4);
            }
        }

        try {
            $waybillParams = [
                'id' => $expressNo,
                'express' => $this->transExpressName($expressName),
                'customerName' => $this->customer_name ?: $last4Num,
            ];
            $config = $this->getKuaidiniaoConfig();
            $waybillParams['EBusinessID'] = current($config);
            $waybillParams['AppKey'] = next($config);
            $waybillParams['SelectType'] = end($config);

            $waybill = new Kuaidiniao($waybillParams);

            $list = $waybill->track()->toArray();
            if (!is_array($list)) {
                throw new \Exception('物流信息查询失败');
            }
            foreach ($list as &$item) {
                $item['datetime'] = $item['time'];
                unset($item['time']);
            }

            $status = $waybill->status;
            if (isset($statusMap[$waybill->status])) {
                $statusText = $statusMap[$waybill->status];
            } else {
                $statusText = '状态未知';
            }
            return [
                'status' => $status,
                'status_text' => $statusText,
                'list' => $list,
            ];
        } catch (\Exception $e) {}
        throw new \Exception('暂无物流信息');
    }

    /**
     * 获取订单收件人手机号
     * @return mixed|string|null
     */
    private function getMobile()
    {
        if ($this->mobile) {
            $mobile = $this->mobile;
        } else {
            $order = null;
            $orderDetailExpress = OrderDetailExpress::find()->where([
                'express' => $this->express,
                'express_no' => $this->express_no,
            ])->orderBy('id DESC')->one();
            if ($orderDetailExpress) {
                $order = Order::findOne($orderDetailExpress->order_id);
            } else {
                $order = Order::find()
                    ->where([
                        'express' => $this->express,
                        'express_no' => $this->express_no,
                    ])->one();
            }
            $mobile = $order ? $order->mobile : null;
        }
        return $mobile;
    }
}
