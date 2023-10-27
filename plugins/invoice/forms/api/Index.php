<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: Jayi
 */
namespace app\plugins\invoice\forms\api;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\invoice\forms\common\Invoice as InvoiceBilling;
use app\plugins\invoice\forms\mall\ApplyOrder;
use app\plugins\invoice\models\Invoice;
use app\plugins\invoice\models\InvoiceSetting;

class Index extends Model
{

    /**
     * 申请开票-新增/编辑
     */
    public function invoicing($param)
    {
        if (!\Yii::$app->user->id) {
            return [
                'code' => ApiCode::CODE_NOT_LOGIN,
                'msg' => '请先登录。',
            ];
        };

        if ($param['id']){
            $model = Invoice::findOne([
                'id' => $param['id'],
            ]);
            $model->status = 6;
            $model->updated_time = time();
            $model->resubmit_time = time();
        }else{
            $model = new Invoice();
            $model->status = 0;
            $model->add_time = time();
        }
        $model->mall_id = \Yii::$app->mall->id;
        $model->uid =  \Yii::$app->user->id;
        $model->order_id =  $param['order_id'];
        $model->title_type =  $param['title_type'];
        $model->buyer_title =  $param['buyer_title'];
        $model->buyer_taxpayer_num =  $param['buyer_taxpayer_num'];
        $model->buyer_address =  $param['buyer_address'];
        $model->buyer_phone =  $param['buyer_phone'];
        $model->buyer_bank_name =  $param['buyer_bank_name'];
        $model->buyer_bank_account =  $param['buyer_bank_account'];
        $model->payee =  $param['payee'];
        $model->buyer_email =  $param['buyer_email'];
        $model->invoice_type_code =  $param['invoice_type_code'];
        $model->remarks =  $param['remarks'];
        $model->medium =  1;  // 发票介质，目前只支持电子

        if ($model->save()) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '申请成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }

    /**
     * 撤销开票
     */
    public function withdraw($param){
        if (empty($param['id']) && !$param['id']){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '参数有误'
            ];
        }
        $model = Invoice::findOne([
            'id' => $param['id'],
        ]);
        $model->status = 3;
        $model->updated_time = time();
        $model->revoke_time = time();
        if ($model->save()) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '撤销成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }

    /**
     * 发送邮箱
     */
    public function sendEmail($param){
        if (empty($param['id']) && !$param['id'] && empty($param['email']) && !$param['email']){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '参数有误'
            ];
        }

        $model = Invoice::findOne([
            'id' => $param['id'],
        ]);
        if($model->status != 2){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '当前审核状态不支持发送邮箱'
            ];
        }
        $model->buyer_email = $param['email'];
        if ($model->save()) {
            $config = InvoiceSetting::find()->asArray()->all();
            $invoice = new InvoiceBilling($config[0]);
            return $invoice->sendEmail($model->toArray());
        } else {
            return $this->getErrorResponse($model);
        }
    }

    /**
     * 发票列表
     */
    public function getList($param)
    {
        if (!\Yii::$app->user->id) {
            return [
                'code' => ApiCode::CODE_NOT_LOGIN,
                'msg' => '请先登录。',
            ];
        };
        $query = invoice::find()->alias('p')
            ->where([
                'p.mall_id' => \Yii::$app->mall->id,
                'p.uid' => \Yii::$app->user->id
            ])
            ->joinWith('user u')
            ->joinWith('order o');

        if ($param['id']){
            $query->andWhere(['p.id'=>$param['id']]);
        }

        $list = $query->page($pagination)
            ->orderBy('p.id DESC')
            ->asArray()
            ->all();

        $data = new ApplyOrder();
        $list = $data->handleData($list);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }
}
