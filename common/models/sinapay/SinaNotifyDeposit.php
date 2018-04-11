<?php

namespace common\models\sinapay;

use Yii;

/**
 * This is the model class for table "sina_notify_deposit".
 *
 * @property integer $id
 * @property string $outer_trade_no
 * @property string $inner_trade_no
 * @property double $deposit_amount
 * @property string $deposit_status
 * @property string $pay_method
 */
class SinaNotifyDeposit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sina_notify_deposit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['outer_trade_no', 'inner_trade_no', 'deposit_amount', 'deposit_status'], 'required'],
            [['deposit_amount'], 'number'],
            [['outer_trade_no', 'inner_trade_no'], 'string', 'max' => 32],
            [['deposit_status'], 'string', 'max' => 20],
            [['pay_method'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'outer_trade_no' => '商户网站唯一订单号',
            'inner_trade_no' => '内部交易凭证号',
            'deposit_amount' => '充值金额',
            'deposit_status' => '充值状态',
            'pay_method' => '充值状态',
        ];
    }
}
