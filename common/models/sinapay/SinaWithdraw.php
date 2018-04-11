<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_withdraw".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $out_trade_no
 * @property string $identity_id
 * @property string $card_id
 * @property double $site_balance
 * @property double $sina_balance
 * @property double $money
 * @property double $money_fund
 * @property double $money_site
 * @property double $money_sina
 * @property integer $type
 * @property integer $status
 * @property string $msg
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaWithdraw extends ActiveRecord
{

    //删除信息
    const STATUS_DELETED = -1;
    //信息错误
    const STATUS_ERROR = 0;
    //用户提现，网站账户信息验证通过
    CONST STATUS_SITE_BALANCE = 1;
    //用户提现，网站完成了赎回操作
    const STATUS_SITE_WITHDRAW = 2;

    //网站完成操作--新浪账户进行进行处理
    CONST STATUS_SINA_BALANCE = 3;
    //新浪账户提现处理接受
    CONST STATUS_SINA_DEAL = 4;
    //用户提现成功--提现查询操作
    const STATUS_SINA_SUCCESS = 5;



    const TYPE_DEF = 0;
    const TYPE_ONE = 1;
    const TYPE_TWO = 2;
    const TYPE_THREE = 3;
    //直接新浪账户提款
    const TYPE_ONLY = 4;

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
        return 'sina_withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'type', 'status', 'create_at', 'update_at'], 'integer'],
            [['site_balance', 'sina_balance', 'money', 'money_fund', 'money_site', 'money_sina'], 'number'],
            [['out_trade_no', 'identity_id', 'msg'], 'string', 'max' => 255],
            [['card_id'], 'string', 'max' => 25]
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
            'out_trade_no' => '提现订单号',
            'identity_id' => '用户标识信息',
            'card_id' => '绑定银行卡号',
            'site_balance' => '网站账户金额',
            'sina_balance' => '新浪账户金额',
            'money' => '用户提现金额',
            'money_fund' => '货币基金收益',
            'money_site' => '网站应赎回金额',
            'money_sina' => '新浪托管应该赎回的金额',
            'type' => '提现类型',
            'status' => '提现状态',
            'msg' => '备注',
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
            self::STATUS_SITE_BALANCE =>'验证通过',
            self::STATUS_SITE_WITHDRAW => "完成赎回",
            self::STATUS_SINA_BALANCE => "网站完成操作",
            self::STATUS_SINA_DEAL =>'新浪账户提现处理接受',
            self::STATUS_SINA_SUCCESS =>'提现成功',
        ];
    }
}
