<?php

namespace common\models\lianlian;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\UcenterMember;
/**
 * This is the model class for table "lianlian_pay".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $idcard
 * @property string $real_name
 * @property string $user_id
 * @property string $busi_partne
 * @property string $no_order
 * @property string $name_goods
 * @property string $money_order
 * @property string $card_no
 * @property string $card_phone
 * @property string $bank_code
 * @property integer $status
 * @property string $remark
 * @property string $sign_type
 * @property string $sign
 * @property string $oid_paybill
 * @property string $money_lianlian
 * @property string $settle_date
 * @property string $pay_type
 * @property integer $create_at
 * @property integer $update_at
 */
class payLL extends ActiveRecord
{
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
        return 'lianlian_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['idcard', 'real_name', 'user_id', 'busi_partne', 'no_order', 'name_goods', 'money_order', 'card_no', 'bank_code', 'remark', 'sign', 'oid_paybill', 'money_lianlian', 'settle_date', 'pay_type', 'from_ip'], 'string', 'max' => 255],
            [['sign_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'idcard' => '身份证号',
            'real_name' => '真实姓名',
            'user_id' => '用户唯一标识',
            'busi_partne' => '商户业务类型',
            'no_order' => '商户订单号',
            'name_goods' => '商品名称',
            'money_order' => '订单金额',
            'card_no' => '银行卡号',
            'from_ip' => '用户请求ip',
            'bank_code' => '银行编号',
            'status' => 'Status',
            'remark' => '备注',
            'sign_type' => '签名方式',
            'sign' => 'Sign',
            'oid_paybill' => '支付单号',
            'money_lianlian' => '连连返回支付结果',
            'settle_date' => '清算日期',
            'pay_type' => '支付类型',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     * @return LianlianPayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LianlianPayQuery(get_called_class());
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'user_id']);
    }
}
