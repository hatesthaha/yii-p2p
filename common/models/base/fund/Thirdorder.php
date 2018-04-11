<?php

namespace common\models\base\fund;

use Yii;

/**
 * This is the model class for table "fund_thirdorder".
 *
 * @property integer $id
 * @property integer $thirdproduct_id
 * @property integer $order_id
 * @property double $money
 * @property integer $status
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $create_at
 * @property integer $update_at
 */
class Thirdorder extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;//订单生效
    const STATUS_DELETED = 2;//订单被删除
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_thirdorder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['thirdproduct_id', 'order_id', 'status', 'start_at', 'end_at', 'create_at', 'update_at'], 'integer'],
            [['money','start_money'], 'double']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thirdproduct_id' => 'Thirdproduct ID',
            'order_id' => 'Order ID',
            'money' => 'Money',
            'status' => 'Status',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
