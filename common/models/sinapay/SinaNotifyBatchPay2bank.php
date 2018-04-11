<?php

namespace common\models\sinapay;

use Yii;

/**
 * This is the model class for table "sina_notify_batch_pay2bank".
 *
 * @property integer $id
 * @property string $batch_no
 * @property string $inner_batch_no
 * @property double $batch_amount
 * @property string $batch_quantity
 * @property string $batch_status
 * @property string $trade_list
 * @property string $gmt_create
 * @property string $gmt_finished
 */
class SinaNotifyBatchPay2bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sina_notify_batch_pay2bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_no', 'inner_batch_no', 'batch_amount', 'batch_quantity', 'batch_status', 'trade_list', 'gmt_create'], 'required'],
            [['batch_amount'], 'number'],
            [['trade_list'], 'string'],
            [['batch_no', 'inner_batch_no'], 'string', 'max' => 32],
            [['batch_quantity'], 'string', 'max' => 10],
            [['batch_status'], 'string', 'max' => 20],
            [['gmt_create', 'gmt_finished'], 'string', 'max' => 14]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'batch_no' => '批次号',
            'inner_batch_no' => '内部批次凭证号',
            'batch_amount' => '商户提交批次订单总金额',
            'batch_quantity' => '商户提交批次订单总笔数',
            'batch_status' => '批次通知状态',
            'trade_list' => '出款订单列表',
            'gmt_create' => '批次创建时间',
            'gmt_finished' => '批次处理结束时间',
        ];
    }
}
