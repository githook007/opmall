<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/19
 * Time: 10:37
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\bootstrap\payment;


use app\models\Model;
use app\models\User;

/**
 * @property User $user
 */
class PaymentTransfer extends Model
{
    const TRANSFER_TYPE_WECHAT = 'wxapp';
    const TRANSFER_TYPE_ALIPAY = 'aliapp';
    const TRANSFER_TYPE_BAIDU = 'bdapp';
    const TRANSFER_TYPE_TOUTIAO = 'ttapp';
    const TRANSFER_TYPE_MOBILE = 'mobile';
    const TRANSFER_TYPE_WECHAT_MP = 'wechat';
    const TRANSFER_TYPE_APP = 'app';  // @czs app

    public $orderNo;
    public $amount;
    public $user;
    public $title;
    public $transferType;

    public function rules()
    {
        return [
            [['orderNo', 'amount', 'user', 'title', 'transferType'], 'required'],
            ['orderNo', 'string', 'max' => 32],
            [['title'], 'string', 'max' => 128],
            [['amount'], function ($attribute, $params) {
                if (!is_float($this->amount) && !is_int($this->amount) && !is_double($this->amount)) {
                    $this->addError($attribute, '`amount`必须是数字类型。');
                }
            }],
            [['amount'], 'number', 'min' => 0.01, 'max' => 100000000],
            [['transferType'], 'in', 'range' => ['wxapp', 'aliapp', 'bdapp', 'ttapp', 'wechat']],
            ['user', function ($attribute, $param) {
                if (!$this->user instanceof User) {
                    $this->addError($attribute, 'user必须是app\\models\User的对象');
                }
            }]
        ];
    }

    /**
     * PaymentTransfer constructor.
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
