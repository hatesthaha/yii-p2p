<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_deposit".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $identity_id
 * @property string $out_trade_no
 * @property string $account_type
 * @property string $amount
 * @property string $payer_ip
 * @property string $pay_method
 * @property string $ticket
 * @property string $validate_code
 * @property integer $status
 * @property string $msg
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaDeposit extends ActiveRecord
{

    //用户删除信息
    const STATUS_DELETED = -1;
    //用户信息错误
    const STATUS_ERROR = 0;
    //用户短信确认中
    CONST STATUS_CONFIRM = 1;
    //用户完成充值行为
    CONST STATUS_SUCCESS = 2;

    // 充值处理中
    const STATUS_PROCESSING = 3;

    private $_statusLabel;
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
        return 'sina_deposit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['identity_id', 'out_trade_no', 'account_type', 'pay_method', 'ticket', 'msg'], 'string', 'max' => 255],
            [['payer_ip', 'validate_code'], 'string', 'max' => 25]
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
            'identity_id' => '用户标识信息',
            'out_trade_no' => '交易订单号',
            'account_type' => '账户类型',
            'amount' => '金额',
            'payer_ip' => '付款用户IP地址',
            'pay_method' => '支付方式',
            'ticket' => 'Ticket',
            'validate_code' => '验证码',
            'status' => '状态',
            'msg' => '返回信息',
            'create_at' => '创建时间',
            'update_at' => 'Update At',
        ];
    }
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::labels();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }
    public static function labels()
    {
        return [
            self::STATUS_DELETED => "删除信息",
            self::STATUS_ERROR => "信息错误",
            self::STATUS_CONFIRM => "短信确认",
            self::STATUS_SUCCESS =>'完成充值',
            self::STATUS_PROCESSING => '充值处理中',
        ];
    }
}
