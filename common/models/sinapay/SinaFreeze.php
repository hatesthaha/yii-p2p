<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_freeze".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $identity_id
 * @property string $account_type
 * @property string $out_freeze_no
 * @property double $freeze_money
 * @property string $freeze_summary
 * @property integer $status
 * @property string $msg
 * @property string $out_unfreeze_no
 * @property double $unfreeze_money
 * @property string $unfreeze_summary
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaFreeze extends ActiveRecord
{
    //删除信息
    const STATUS_DELETED = -1;
    //信息错误
    const STATUS_ERROR = 0;
    //冻结成功
    CONST STATUS_FREEZE = 1;
    //解冻成功
    CONST STATUS_UNFREEZE = 2;
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
        return 'sina_freeze';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['freeze_money', 'unfreeze_money'], 'number'],
            [['identity_id', 'account_type', 'out_freeze_no', 'freeze_summary', 'msg', 'out_unfreeze_no', 'unfreeze_summary'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户id',
            'identity_id' => '用户标识',
            'account_type' => '账户类型',
            'out_freeze_no' => '冻结订单号',
            'freeze_money' => '冻结金额',
            'freeze_summary' => '冻结原因',
            'status' => '状态',
            'msg' => '信息',
            'out_unfreeze_no' => '解冻单号',
            'unfreeze_money' => '解冻资金',
            'unfreeze_summary' => '解冻原因',
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
            self::STATUS_FREEZE => "冻结成功",
            self::STATUS_UNFREEZE =>'解冻成功',
        ];
    }
}
