<?php

namespace common\models\sinapay;

use Yii;

/**
 * This is the model class for table "sina_notify_trade".
 *
 * @property integer $id
 * @property string $outer_trade_no
 * @property string $inner_trade_no
 * @property string $trade_status
 * @property double $trade_amount
 * @property string $gmt_create
 * @property string $gmt_payment
 * @property string $gmt_close
 * @property string $pay_method
 */
class SinaNotifyTrade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sina_notify_trade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['outer_trade_no', 'inner_trade_no', 'trade_status', 'trade_amount', 'gmt_create'], 'required'],
            [['trade_amount'], 'number'],
            [['outer_trade_no', 'inner_trade_no'], 'string', 'max' => 32],
            [['trade_status'], 'string', 'max' => 20],
            [['gmt_create', 'gmt_payment', 'gmt_close'], 'string', 'max' => 14],
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
            'trade_status' => '交易状态',
            'trade_amount' => '交易金额',
            'gmt_create' => '交易创建时间',
            'gmt_payment' => '交易支付时间',
            'gmt_close' => '交易关闭时间',
            'pay_method' => '支付方式',
        ];
    }
}
