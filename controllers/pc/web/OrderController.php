<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c)
* author: opmall
*/

namespace app\controllers\pc\web;

use app\controllers\pc\web\filters\LoginFilter;
use app\bootstrap\response\ApiCode;
use app\forms\api\order\OrderExpressForm;
use app\forms\pc\order\OrderEditForm;
use app\forms\pc\order\OrderForm;
use app\forms\pc\order\OrderPayForm;
use app\forms\pc\order\OrderPayResultForm;
use app\forms\pc\order\OrderRetryPayForm;
use app\forms\pc\order\OrderSubmitForm;

class OrderController extends CommonController
{
    public function behaviors(){
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     * 结算页预览
     */
    public function actionPreview(){
        $form = new OrderSubmitForm();
        $form->form_data = \Yii::$app->serializer->decode(\Yii::$app->request->post('form_data'));
        return $this->asJson($form->preview());
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     * 提交订单
     */
    public function actionSubmit(){
        $form = new OrderSubmitForm();
        $form->form_data = \Yii::$app->serializer->decode(\Yii::$app->request->post('form_data'));
        $mallPaymentTypes = \Yii::$app->mall->getMallSettingOne('payment_type');
        return $this->asJson($form->setSupportPayTypes($mallPaymentTypes)->submit());
    }

    /**
     * @return \yii\web\Response
     * 生成订单并去付款
     */
    public function actionPayData(){
        $form = new OrderPayForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->getResponseData());
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \app\bootstrap\payment\PaymentException
     * 重新付款
     */
    public function actionAgainPay(){
        $form = new OrderRetryPayForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->getResponseData());
    }

    /**
     * @return \yii\web\Response
     * 监听支付结果
     */
    public function actionPayResult(){
        $form = new OrderPayResultForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->search());
    }

    /**
     * 订单列表
     * @return \yii\web\Response
     */
    public function actionList()
    {
        $form = new OrderForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->getList());
    }

    /**
     * 订单详情
     * @return \yii\web\Response
     */
    public function actionDetail()
    {
        $form = new OrderEditForm();
        $form->attributes = $this->getParams();

        return $this->asJson($form->getDetail());
    }

    /**
     * 售后详情
     * @return \yii\web\Response
     */
    public function actionApplyRefund()
    {
        $form = new OrderRefundForm();
        $form->attributes = \Yii::$app->request->get();

        return $this->asJson($form->getDetail());
    }

    /**
     * 售后 生成退换货订单
     * @return \yii\web\Response
     */
    public function actionRefundSubmit()
    {
        $form = new OrderRefundSubmitForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->submit());
    }

    /**
     * 售后 退换货用户 发货
     * @return \yii\web\Response
     */
    public function actionRefundSend()
    {
        $form = new OrderRefundSendForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->send());
    }

    /**
     * 售后 退换货订单详情
     * @return \yii\web\Response
     */
    public function actionRefundDetail()
    {
        $form = new OrderRefundForm();
        $form->attributes = \Yii::$app->request->get();

        return $this->asJson($form->getOrderRefundDetail());
    }

    /**
     * 订单确认收货
     * @return \yii\web\Response
     */
    public function actionConfirm()
    {
        $form = new OrderForm();
        $form->attributes = $this->getParams();

        return $this->asJson($form->orderConfirm());
    }

    /**
     * 订单取消 | 申请取消退款
     * @return \yii\web\Response
     */
    public function actionCancel()
    {
        $form = new OrderForm();
        $form->attributes = $this->getParams();

        return $this->asJson($form->orderCancel());
    }

    /**
     * 订单评价
     * @return \yii\web\Response
     */
    public function actionAppraise()
    {
        $form = new  OrderAppraiseForm();
        $form->appraiseData = \Yii::$app->request->post('appraiseData');
        $form->order_id = \Yii::$app->request->post('order_id');

        return $this->asJson($form->appraise());
    }

    /**
     * 订单物流详情
     * @return \yii\web\Response
     */
    public function actionExpressDetail()
    {
        $form = new OrderExpressForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->search());
    }

    public function actionCancelCauseList()
    {
        $res = [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => [
                    '多买/错买/不想要',
                    '未按时间发货',
                    '地址填写错误',
                    '缺货',
                    '其它'
                ]
            ]
        ];
        return $this->asJson($res);
    }

    // 用户撤销申请退款（未发货）
    public function actionCancelApply()
    {
        $form = new OrderForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->cancelApply());
    }

    // 用户撤销售后申请
    public function actionCancelRefund()
    {
        $form = new CancelRefundForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->cancelRefund());
    }
}
