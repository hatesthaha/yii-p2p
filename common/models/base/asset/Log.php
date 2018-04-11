<?php

namespace common\models\base\asset;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\UcenterMember;
/**
 * This is the model class for table "asset_log".
 *
 * @property integer $id
 * @property integer $member_id
 * @property double $step
 * @property string $action
 * @property integer $status
 * @property string $bankcard
 * @property string $remark
 * @property integer $create_at
 * @property integer $update_at
 *
 * @property UcenterMember $member
 */
class Log extends ActiveRecord
{
    //投资
    const STATUS_INVEST_SUC = 2;
    const STATUS_INVEST_ERR = -2;
    //提现
    const STATUS_WITHDRAW_SUC = 4;
    const STATUS_WITHDRAW_ERR = -4;
    //赎回
    const STATUS_REDEM_SUC = 3;
    const STATUS_REDEM_ERR = -3;
    //充值
    const STATUS_RECHAR_SUC = 1;
    const STATUS_RECHAR_ERR = -1;

    const STATUS_PROCESSING = 0;

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
        return 'asset_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'create_at', 'update_at'], 'integer'],
            [['step'], 'number'],
            [['action'], 'string', 'max' => 100],
            [['bankcard','trade_no'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', '用户名'),
            'step' => Yii::t('app', '金额'),
            'action' => Yii::t('app', '操作方法'),
            'status' => Yii::t('app', '状态'),
            'bankcard' => Yii::t('app', '银行卡'),
            'trade_no'=> Yii::t('app','交易订单号'),
            'remark' => Yii::t('app', '操作备注'),
            'create_at' => Yii::t('app', '创建时间'),
            'update_at' => Yii::t('app', '更新时间'),
        ];
    }


    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }
    public static function getArrayStatus()
    {
        return [
            self::STATUS_WITHDRAW_SUC => Yii::t('app', '提现成功'),
            self::STATUS_WITHDRAW_ERR => Yii::t('app', '提现失败'),
            self::STATUS_INVEST_SUC => Yii::t('app', '投资成功'),
            self::STATUS_INVEST_ERR => Yii::t('app', '投资失败'),
            self::STATUS_REDEM_SUC => Yii::t('app', '赎回成功'),
            self::STATUS_REDEM_ERR => Yii::t('app', '赎回失败'),
            self::STATUS_RECHAR_SUC => Yii::t('app', '充值成功'),
            self::STATUS_RECHAR_ERR => Yii::t('app', '充值失败'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'member_id']);
    }
}
