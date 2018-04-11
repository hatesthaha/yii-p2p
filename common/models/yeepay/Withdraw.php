<?php

namespace common\models\yeepay;

use Yii;
use common\models\UcenterMember;
/**
 * This is the model class for table "yeepay_withdraw".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $identityid
 * @property string $identitytype
 * @property string $card_top
 * @property string $card_last
 * @property integer $amount
 * @property string $userip
 * @property string $ybdrawflowid
 * @property integer $ybamount
 * @property integer $status
 * @property string $msg
 * @property integer $create_at
 * @property integer $update_at
 */
class Withdraw extends \yii\db\ActiveRecord
{
    const STATUS_SUC =1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yeepay_withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'amount', 'ybamount', 'status', 'create_at', 'update_at'], 'integer'],
            [['identityid', 'identitytype', 'card_top', 'card_last', 'userip', 'ybdrawflowid', 'msg'], 'string', 'max' => 255]
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
            'identityid' => '商户请求号',
            'identitytype' => '用户标识',
            'card_top' => '卡号前 6 位',
            'card_last' => '卡号后 4 位',
            'amount' => '提现金额',
            'userip' => 'Userip',
            'ybdrawflowid' => '易宝流水号',
            'ybamount' => '易宝返回金额',
            'status' => '状态',
            'msg' => '信息',
            'create_at' => '创建时间',
            'update_at' => 'Update At',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'uid']);
    }
}
