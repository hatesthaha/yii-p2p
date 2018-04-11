<?php

namespace common\models\sinapay;

use Yii;

/**
 * This is the model class for table "sina_notify_withdraw".
 *
 * @property integer $id
 * @property string $outer_trade_no
 * @property string $inner_trade_no
 * @property double $withdraw_amount
 * @property string $withdraw_status
 * @property string $card_id
 */
class SinaNotifyWithdraw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sina_notify_withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['outer_trade_no', 'inner_trade_no', 'withdraw_amount', 'withdraw_status'], 'required'],
            [['withdraw_amount'], 'number'],
            [['outer_trade_no', 'inner_trade_no', 'card_id'], 'string', 'max' => 32],
            [['withdraw_status'], 'string', 'max' => 20]
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
            'withdraw_amount' => '提现金额',
            'withdraw_status' => '提现状态',
            'card_id' => '银行卡ID',
        ];
    }
}
