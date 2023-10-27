<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/11 11:26
 */


namespace app\bootstrap\payment;


use yii\base\Model;

class PaymentOrder extends Model
{
    const PAY_TYPE_HUODAO = 'huodao';
    const PAY_TYPE_BALANCE = 'balance';
    const PAY_TYPE_WECHAT = 'wechat';
    const PAY_TYPE_ALIPAY = 'alipay';
    const PAY_TYPE_BAIDU = 'baidu';
    const PAY_TYPE_TOUTIAO = 'toutiao';
    const PAY_TYPE_WECHAT_H5 = 'wechat_h5';
    const PAY_TYPE_ALIPAY_H5 = 'alipay_h5';
    const PAY_TYPE_WECHAT_SCAN = 'wechat_scan';
    const PAY_TYPE_ALIPAY_SCAN = 'alipay_scan';
    const PAY_TYPE_POS = 'pos';
    const PAY_TYPE_CASH = 'cash';
    const PAY_TYPE_UNIONPAY = 'unionpay';
    const PAY_TYPE_WECHAT_PAY_FOR_ANOTHER = 'wechat_pay_for_another'; // 代付 @czs

    public $orderNo;
    public $amount;
    public $title;
    public $notifyClass;
    public $supportPayTypes;
    public $payType;

    public $openid; // 付款者openid  @czs

    public function rules()
    {
        return [
            [['orderNo', 'amount', 'title', 'notifyClass'], 'required',],
            [['orderNo'], 'string', 'max' => 32],
            [['title', 'openid'], 'string', 'max' => 128],
            [['notifyClass'], 'string', 'max' => 512],
            [['amount'], function ($attribute, $params) {
                if (!is_float($this->amount) && !is_int($this->amount) && !is_double($this->amount)) {
                    $this->addError($attribute, '`amount`必须是数字类型。');
                }
            }],
            [['amount'], 'number', 'min' => 0, 'max' => 100000000],
            [['payType'], 'safe'],
            [['supportPayTypes'], 'safe'],
        ];
    }

    /**
     * PaymentOrder constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        if (!$this->validate()) {
            dd($this->errors);
        }
    }
}
