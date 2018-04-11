<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_ransom".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $identity_id
 * @property string $out_trade_no
 * @property string $summary
 * @property string $trade_close_time
 * @property string $payer_id
 * @property string $payer_ip
 * @property string $pay_method
 * @property double $money_sina
 * @property string $payee_out_trade_no
 * @property integer $status
 * @property integer $msg
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaRansom extends ActiveRecord
{

    //错误
    const STATUS_ERROR = 0;
    //新浪代收成功
    CONST STATUS_TRADE= 1;
    //新浪代付成功
    CONST STATUS_PAY_TRADE = 2;
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
        return 'sina_ransom';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['money_sina'], 'number'],
            [['identity_id', 'out_trade_no', 'summary', 'payer_id', 'pay_method', 'payee_out_trade_no','msg'], 'string', 'max' => 255],
            [['trade_close_time', 'payer_ip'], 'string', 'max' => 25]
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
            'identity_id' => '赎回人用户标识',
            'out_trade_no' => '新浪代收订单信息',
            'summary' => '代收备注',
            'trade_close_time' => '代收有效时间',
            'payer_id' => '新浪代收人标识信息',
            'payer_ip' => '操作ip',
            'pay_method' => '代收付款方式',
            'money_sina' => '新浪代收金额',
            'payee_out_trade_no' => '新浪代付订单号',
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
            self::STATUS_ERROR => "信息错误",
            self::STATUS_TRADE => "新浪代收成功",
            self::STATUS_PAY_TRADE => "新浪代付成功",

        ];
    }
}
