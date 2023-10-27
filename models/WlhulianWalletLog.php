<?php

namespace app\models;

/**
 * This is the model class for table "{{%wlhulian_wallet_log}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property string $order_no
 * @property float $money     余额
 * @property float $balance
 * @property int $type          类型；1：充值；2：扣除；3：派单；4：退单
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 * @property User $user
 */
class WlhulianWalletLog extends ModelActiveRecord
{
    const ADD = 1;
    const DEC = 2;
    const SEND = 3;
    const BACK = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wlhulian_wallet_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id'], 'required'],
            [['mall_id', 'user_id', 'type'], 'integer'],
            [['money', 'balance'], 'number'],
            [['order_no'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'is_delete' => 'Is Delete',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
