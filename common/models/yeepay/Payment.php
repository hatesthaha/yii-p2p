<?php

namespace common\models\yeepay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\UcenterMember;
/**
 * This is the model class for table "yeepay_payment".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $orderid
 * @property integer $transtime
 * @property integer $amount
 * @property string $userip
 * @property string $productname
 * @property string $identityid
 * @property integer $orderexpdate
 * @property string $phone
 * @property integer $status
 * @property integer $sendtime
 * @property string $yborderid
 * @property integer $ybamount
 * @property string $msg
 * @property integer $create_at
 * @property integer $update_at
 */
class Payment extends ActiveRecord
{
    const STATUS_SUC = 1;
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
        return 'yeepay_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'transtime', 'amount', 'orderexpdate', 'status', 'sendtime', 'ybamount', 'create_at', 'update_at'], 'integer'],
            [['orderid', 'productname', 'identityid', 'phone', 'yborderid', 'msg'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户名',
            'orderid' => '商户订单号',
            'transtime' => '交易时间',
            'amount' => '交易金额',
            'userip' => 'Userip',
            'productname' => '商品名称',
            'identityid' => 'Identityid',
            'orderexpdate' => '订单有效期',
            'phone' => '电话',
            'status' => '状态码',
            'sendtime' => '短信发生时间',
            'yborderid' => '易宝交易流水号',
            'ybamount' => '易宝交易金额',
            'msg' => '提示信息',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     * @return PaymentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentQuery(get_called_class());
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'uid']);
    }
}
