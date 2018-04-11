<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_invest".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $identity_id
 * @property string $out_trade_no
 * @property string $summary
 * @property string $trade_close_time
 * @property string $payer_ip
 * @property string $pay_type
 * @property string $account_type
 * @property string $goods_id
 * @property string $money
 * @property integer $status
 * @property string $msg
 * @property string $payee_out_trade_no
 * @property string $payee_identity_id
 * @property string $payee_account_type
 * @property string $payee_amount
 * @property string $payee_summary
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaInvest extends ActiveRecord
{

    //用户删除信息
    const STATUS_DELETED = -1;
    //用户信息错误
    const STATUS_ERROR = 0;
    //用户完成投资
    CONST STATUS_SUCCESS = 1;
    //
    //网站获得投资金额
    CONST STATUS_PAYEE_SUCCESS = 2;
    //用户投资失败，中间账户退款成功
    const STATUS_REFUND_SUCCESS = 3;
    //用户投资失败，中间账户退款失败
    const STATUS_REFUND_ERROR = -3;
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
        return 'sina_invest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['money', 'payee_amount', 'refund_amount'], 'number'],
            [['identity_id', 'out_trade_no', 'summary', 'msg', 'payee_out_trade_no', 'payee_identity_id','refund_out_trade_no','refund_summary'], 'string', 'max' => 255],
            [['trade_close_time', 'pay_type', 'account_type', 'goods_id'], 'string', 'max' => 25],
            [['payer_ip', 'payee_account_type', 'payee_summary'], 'string', 'max' => 50]
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
            'identity_id' => 'Identity ID',
            'out_trade_no' => '订单号',
            'summary' => '订单简介',
            'trade_close_time' => '订单有效时间',
            'payer_ip' => '支付ip',
            'pay_type' => '支付方式',
            'account_type' => '账户类型',
            'goods_id' => '标的标号',
            'money' => '投资金额',
            'status' => '状态',
            'msg' => '返回消息',
            'payee_out_trade_no' => '网站收款订单号',
            'payee_identity_id' => '收款人标识信息',
            'payee_account_type' => '收款人账户信息',
            'payee_amount' => '收款金额',
            'payee_summary' => '收款简介',
            'refund_out_trade_no' => '退款订单号',
            'refund_amount' => '退款金额',
            'refund_summary' => '退款摘要',
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
            self::STATUS_SUCCESS =>'完成投资',
            self::STATUS_PAYEE_SUCCESS => "网站获得投资金额",
            self::STATUS_REFUND_SUCCESS => "退款成功",
            self::STATUS_REFUND_ERROR =>'退款失败',
        ];
    }
}
