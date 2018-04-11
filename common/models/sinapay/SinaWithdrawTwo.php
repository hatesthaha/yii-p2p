<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_withdraw_two".
 *
 * @property integer $id
 * @property integer $uid
 * @property double $money
 * @property string $identity_id
 * @property string $collect_status
 * @property string $out_trade_no
 * @property string $payee_identity_id
 * @property string $summary
 * @property string $hosting_status
 * @property string $batch_no
 * @property string $list_no
 * @property string $list_name
 * @property string $list_idcard
 * @property string $list_bank_account_no
 * @property string $detail_list
 * @property integer $status
 * @property string $msg
 * @property string $create_time
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaWithdrawTwo extends ActiveRecord
{
    //删除
    const STATUS_DELETED = -1;
    //错误
    const STATUS_ERROR = 0;
    //确认中
    CONST STATUS_CONFIRM = 1;
    //完成
    CONST STATUS_SUCCESS = 2;

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
        return 'sina_withdraw_two';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['money'], 'number'],
            [['detail_list'], 'string'],
            [['identity_id', 'collect_status', 'out_trade_no', 'payee_identity_id', 'summary', 'hosting_status', 'batch_no', 'list_no', 'list_name', 'list_idcard', 'list_bank_account_no', 'msg', 'create_time'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '提现用户id',
            'money' => '提现金额',
            'identity_id' => '用户标识',
            'collect_status' => '新浪代收情况',
            'out_trade_no' => '新浪代付商户2订单号',
            'payee_identity_id' => '收款者标识',
            'summary' => '简介',
            'hosting_status' => '代付状态',
            'batch_no' => '批量代付银行卡订单号',
            'list_no' => '组合订单号',
            'list_name' => '提款者姓名',
            'list_idcard' => '提款者身份证号',
            'list_bank_account_no' => '提款到的银行卡号',
            'detail_list' => '组合信息',
            'status' => '提现状态',
            'msg' => '备注信息',
            'create_time' => '发送时间',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     * @return SinaWithdrawTwoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SinaWithdrawTwoQuery(get_called_class());
    }
}
