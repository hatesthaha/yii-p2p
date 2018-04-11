<?php

namespace common\models\sinapay;

use Yii;

/**
 * This is the model class for table "sina_notify_refund".
 *
 * @property integer $id
 * @property string $orig_outer_trade_no
 * @property string $outer_trade_no
 * @property string $inner_trade_no
 * @property double $refund_amount
 * @property string $refund_status
 * @property string $gmt_refund
 */
class SinaNotifyRefund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sina_notify_refund';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orig_outer_trade_no', 'outer_trade_no', 'inner_trade_no', 'refund_amount', 'refund_status', 'gmt_refund'], 'required'],
            [['refund_amount'], 'number'],
            [['orig_outer_trade_no', 'outer_trade_no', 'inner_trade_no'], 'string', 'max' => 32],
            [['refund_status'], 'string', 'max' => 20],
            [['gmt_refund'], 'string', 'max' => 14]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orig_outer_trade_no' => '原交易商户网站唯一订单号',
            'outer_trade_no' => '商户网站唯一订单号',
            'inner_trade_no' => '内部交易凭证号',
            'refund_amount' => '退款金额',
            'refund_status' => '退款状态',
            'gmt_refund' => '交易退款时间',
        ];
    }
}
