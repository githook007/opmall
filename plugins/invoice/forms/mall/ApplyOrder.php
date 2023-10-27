<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: Jayi
 */
namespace app\plugins\invoice\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\mall\order\OrderDetailForm;
use app\models\Model;
use app\plugins\invoice\models\InvoiceSetting;
use app\plugins\invoice\models\Invoice;
use app\plugins\invoice\forms\common\Invoice as InvoiceBilling;

class ApplyOrder extends Model
{
    public $status = 0;
    public $nickname;
    public $order_sn;
    public $invoice_type_code;
    public $date_start;
    public $date_end;

    public function rules()
    {
        return [
            [['status'], 'in', 'range' => [0, 1, 2, 3, 4, 5, 6]],
            [['invoice_type_code'], 'in', 'range' => ['004', '007', '025', '026', '028', '032']],
            [['nickname', 'date_start', 'date_end'], 'string'],
            [['nickname', 'date_start', 'date_end'], 'default', 'value' => ''],
            [['order_sn', 'date_start', 'date_end'], 'string'],
        ];
    }

    //GET
    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        };
        $query = invoice::find()->alias('p')
            ->where([
                'p.mall_id' => \Yii::$app->mall->id,
            ])
            ->joinWith('user u')
            ->joinWith('order o')
            ->keyword($this->status, ['p.status' => $this->status])
            ->keyword($this->invoice_type_code, ['p.invoice_type_code' => $this->invoice_type_code]);

        if ($this->nickname){
            $query = $query->andWhere([
                'or',
                ['like','u.nickname',$this->nickname],
                ['like','u.mobile',$this->nickname],
            ]);
        }
        if ($this->order_sn){
            $query = $query->andWhere([
                'or',
                ['like','o.order_no',$this->order_sn],
            ]);
        }
        if (empty($this->status)){
            if ($this->status == 3){
                $query = $query->andWhere(['in', 'p.status', ['3', '4']]);
            }elseif ($this->status == 0){
                $query = $query->andWhere(['in', 'p.status', ['0', '6']]);
            }else{
                $query = $query->andWhere(['p.status' => $this->status]);
            }
        }
        if ($this->date_start){
            $query = $query->andWhere(['>', 'p.add_time', strtotime($this->date_start)]);
            $query = $query->andWhere(['<', 'p.add_time', strtotime($this->date_end.'+1 day')]);
        }
        $list = $query->page($pagination)
            ->orderBy('p.id DESC')
            ->asArray()
            ->all();

        $list = $this->handleData($list);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    //GET
    public function getDetail($id = 0)
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        };
        $query = invoice::find()->alias('p')
            ->where([
                'p.mall_id' => \Yii::$app->mall->id,
            ])
            ->joinWith('user u')
            ->keyword($this->status, ['p.status' => $this->status])
            ->keyword($this->invoice_type_code, ['p.invoice_type_code' => $this->invoice_type_code]);
        if ($id){
            $query->andWhere(['p.id'=>$id]);
        }
        $list = $query->page($pagination)
            ->orderBy('p.id DESC')
            ->asArray()
            ->all();

        $list = $this->handleData($list, 'detail');

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    /**
     * 处理数据
     */
    public function handleData($list, $key = ''){
        foreach ($list as &$item){
            $item['nickname'] = $item['user']['nickname'];
            $item['add_time'] = date('Y-m-d', $item['add_time']);
            if ($key != 'agree'){
                switch ($item['medium']) {
                    case '1':
                        $item['medium'] = '电子';
                        break;
                    default:
                        $item['medium'] = '纸质';
                        break;
                }
                switch ($item['invoice_type_code']) {
                    case '004':
                        $item['invoice_type_code'] = '增值税专用发票';
                        break;
                    case '007':
                        $item['invoice_type_code'] = '增值税普通发票';
                        break;
                    case '025':
                        $item['invoice_type_code'] = '增值税卷式发票';
                        break;
                    case '026':
                        $item['invoice_type_code'] = '增值税电子普通发票';
                        break;
                    case '028':
                        $item['invoice_type_code'] = '增值税电子专用发票';
                        break;
                }
            }
            if ($key != ''){
                $form = new OrderDetailForm();
                $form->attributes = ['order_id'=>$item['order_id']];
                $item['order'] = $form->search()['data']['order'];
            }
            $item['total_pay_price'] = $item['order']['total_pay_price'];
        }
        return $list;
    }

    /**
     * 同意开票
     * @param $data array 条件数据
     */
    public function agreeRefusal($id = 0){
        $query = invoice::find()->alias('p')
            ->where([
                'p.mall_id' => \Yii::$app->mall->id,
            ])
            ->joinWith('user u')
            ->keyword($this->status, ['p.status' => $this->status])
            ->keyword($this->invoice_type_code, ['p.invoice_type_code' => $this->invoice_type_code]);
        if ($id){
            $query->where(['p.id'=>$id]);
        }
        $list = $query->page($pagination)
            ->orderBy('p.id DESC')
            ->asArray()
            ->all();

        $list = $this->handleData($list, 'agree');
        $config = InvoiceSetting::find()->asArray()->all();
        $invoice = new InvoiceBilling($config[0]);
        return $invoice->goInvoice($list[0]);
    }

    /**
     * 拒绝开票
     * @param $data array 条件数据
     */
    public function refuseRefusal($data){
        $invoice = invoice::findOne([
            'id' => $data['id']
        ]);
        if (!$invoice) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '申请不存在，请刷新后重试',
            ];
        }
        $invoice->refusal = $data['refusal'];
        $invoice->status = 5;
        $invoice->examine_time = time();
        if ($invoice->save()) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '拒绝成功',
            ];
        } else {
            return $this->getErrorResponse($invoice);
        }
    }
}
