<?php

namespace app\plugins\invoice\models;

use app\plugins\advance\models\Order;
use Yii;
use app\models\ModelActiveRecord;
use app\models\User;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $uid
 * @property int $order_id
 * @property int $title_type 抬头类型：1：个人、政府事业单位；2：企业
 * @property string $buyer_title 购方名称
 * @property string $buyer_taxpayer_num 购方纳税人识别号
 * @property string $buyer_address 购方地址
 * @property string $buyer_phone 购方电话
 * @property string $buyer_bank_name 购方银行名称
 * @property string $buyer_bank_account 购方银行账号
 * @property string $payee 收款人姓名
 * @property string $buyer_email 收票人邮箱
 * @property string $invoice_type_code 开具发票类型 004：增值税专用发票; 007：增值税普通发票; 025：增值税卷式发票; 026：增值税电子普通发票; 028：增值税电子专用发票; 032：区块链电子发票
 * @property int $medium 发票介质  1电子  2纸质
 * @property string $remarks 备注
 * @property int $status 状态：0：审核中；1审核成功；2开票成功；3发票撤销；4发票红冲；5审核失败；6再次提交
 * @property string $refusal 拒绝原因
 * @property string $add_time 申请时间
 * @property string $updated_time 修改时间
 * @property string $examine_time 审核时间
 * @property string $revoke_time 撤销/红冲时间
 * @property string $resubmit_time 再次提交时间
 * @property string $order_sn 高灯返回order_sn
 * @property string $invoice_id 高灯返回invoice_id
 * @property string $pdf_url pdf存储路径
 * @property string $pdf_img pdf图片存储路径
 */
class Invoice extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'title_type', 'status', 'medium'], 'integer'],
            [['order_id', 'title_type', 'status', 'invoice_type_code', 'medium'], 'required'],
            [['buyer_title', 'buyer_taxpayer_num', 'buyer_address', 'buyer_phone', 'buyer_bank_name', 'buyer_bank_account', 'remarks', 'refusal', 'invoice_type_code', 'payee', 'order_sn', 'invoice_id', 'pdf_url', 'pdf_img'], 'string'],
            [['add_time', 'updated_time', 'examine_time', 'revoke_time', 'resubmit_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => '用户id',
            'uid' => '订单ID',
            'order_id' => '订单ID',
            'title_type' => '抬头类型：1：个人、政府事业单位；2：企业',
            'buyer_title' => '购方名称',
            'buyer_taxpayer_num' => '购方纳税人识别号',
            'buyer_address' => '购方地址',
            'buyer_phone' => '购方电话',
            'buyer_bank_name' => '购方银行名称',
            'buyer_bank_account' => '购方银行账号',
            'payee' => '收款人姓名',
            'buyer_email' => '收票人邮箱',
            'invoice_type_code' => '开具发票类型 004：增值税专用发票; 007：增值税普通发票; 025：增值税卷式发票; 026：增值税电子普通发票; 028：增值税电子专用发票; 032：区块链电子发票',
            'medium' => '发票介质  1电子  2纸质',
            'remarks' => '备注',
            'status' => '状态：0：审核中；1审核成功；2开票成功；3发票撤销；4发票红冲；5审核失败；6再次提交',
            'refusal' => '拒绝原因',
            'add_time' => '申请时间',
            'updated_time' => '修改时间',
            'examine_time' => '审核时间',
            'revoke_time' => '撤销/红冲时间',
            'resubmit_time' => '再次提交时间',
            'order_sn' => '高灯返回order_sn',
            'invoice_id' => '高灯返回invoice_id',
            'pdf_url' => 'pdf存储路径',
            'pdf_img' => 'pdf图片存储路径',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id'])->alias('o');
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'uid'])->alias('u');
    }
}
