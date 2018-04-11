<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_bank".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $identity_id
 * @property string $request_no
 * @property string $bank_code
 * @property string $bank_account_no
 * @property string $card_type
 * @property string $card_attribute
 * @property string $phone_no
 * @property string $province
 * @property string $city
 * @property string $bank_branch
 * @property string $ticket
 * @property string $valid_code
 * @property string $card_id
 * @property integer $status
 * @property string  $msg
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaBank extends ActiveRecord
{

    //用户删除信息
    const STATUS_DELETED = -1;
    //用户信息错误
    const STATUS_ERROR = 0;
    //用户短信确认中
    CONST STATUS_CONFIRM = 1;
    //用户完成绑定
    CONST STATUS_BINGING = 2;
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
        return 'sina_bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at','today_deposit_number','total_deposit_number'], 'integer'],
            [['today_money'], 'number'],
            [['identity_id', 'bank_branch', 'ticket', 'card_id','msg','request_no','bank_name'], 'string', 'max' => 255],
            [['bank_code', 'card_type', 'card_attribute', 'phone_no', 'valid_code'], 'string', 'max' => 25],
            [['bank_account_no', 'province', 'city'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '网站用户id',
            'identity_id' => '用户信息标识',
            'request_no' => '绑卡请求号',
            'bank_code' => '银行编号',
            'bank_name' => '银行名称',
            'bank_account_no' => '银行卡号',
            'card_type' => '银行卡类型',
            'card_attribute' => '银行卡属性',
            'phone_no' => '银行预留手机',
            'province' => '开卡省份',
            'city' => '开卡城市',
            'bank_branch' => '支行名称',
            'ticket' => '推进参数',
            'valid_code' => '短信验证码',
            'card_id' => '钱包系统卡ID',
            'status' => '状态',
            'msg'=> '备注',
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
            self::STATUS_BINGING =>'完成绑定认证',
        ];
    }
}
