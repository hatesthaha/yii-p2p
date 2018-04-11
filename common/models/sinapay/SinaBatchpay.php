<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_batchpay".
 *
 * @property integer $id
 * @property string $out_pay_no
 * @property string $collect_pay_no
 * @property string $trade_list
 * @property integer $status
 * @property string $msg
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaBatchpay extends ActiveRecord
{

    //删除
    const STATUS_DELETED = -1;
    //错误
    const STATUS_ERROR = 0;
    //成功
    CONST STATUS_SUCCESS = 1;


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sina_batchpay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_at', 'update_at'], 'integer'],
            [['trade_list'], 'string'],
            [['out_pay_no', 'collect_pay_no', 'msg'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'out_pay_no' => '批量代付订单号',
            'collect_pay_no' => '代付对应的代收订单号',
            'trade_list' => '交易参数',
            'status' => 'Status',
            'msg' => 'Msg',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
