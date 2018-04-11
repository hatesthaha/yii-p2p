<?php

namespace common\models\sinapay;

use Yii;

/**
 * This is the model class for table "sina_notify_batch_trade".
 *
 * @property integer $id
 * @property string $outer_batch_no
 * @property string $inner_batch_no
 * @property string $batch_quantity
 * @property double $batch_amount
 * @property string $batch_status
 * @property string $trade_list
 * @property string $gmt_create
 * @property string $gmt_finished
 */
class SinaNotifyBatchTrade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sina_notify_batch_trade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['outer_batch_no', 'inner_batch_no', 'batch_quantity', 'batch_amount', 'batch_status', 'trade_list', 'gmt_create'], 'required'],
            [['batch_amount'], 'number'],
            [['trade_list'], 'string'],
            [['outer_batch_no', 'inner_batch_no', 'batch_quantity'], 'string', 'max' => 32],
            [['batch_status'], 'string', 'max' => 20],
            [['gmt_create'], 'string', 'max' => 14],
            [['gmt_finished'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'outer_batch_no' => '商户网站唯一订单批次号',
            'inner_batch_no' => '内部交易订单批次号',
            'batch_quantity' => '批次总交易笔数',
            'batch_amount' => '批次总金额',
            'batch_status' => '批次状态',
            'trade_list' => '交易明细列表',
            'gmt_create' => '交易创建时间',
            'gmt_finished' => '批次处理结束时间',
        ];
    }
}
