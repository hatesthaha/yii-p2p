<?php

namespace common\models\base\activity;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\base\fund\Order;
use common\models\UcenterMember;
/**
 * This is the model class for table "activity_raise_card".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $coupon_id
 * @property integer $validity_start_at
 * @property integer $use_at
 * @property integer $validity_out_at
 * @property integer $use_end_time
 * @property double $rate
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 *
 * @property FundOrders $fundOrder
 * @property UcenterMember $member
 */
class RaiseCard extends \yii\db\ActiveRecord
{

    const STATUS_USE = 1;
    private $_statusLabel;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_raise_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['member_id', 'fund_order_id', 'use_start_at', 'use_at', 'use_out_at', 'validity_time', 'status', 'create_at', 'update_at'], 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', '会员ID'),
            'coupon_id' => Yii::t('app', '增息卡'),
            'validity_start_at' => Yii::t('app', '有效期开始时间'),
            'use_at' => Yii::t('app', '使用时间'),
            'validity_out_at' => Yii::t('app', '有效期结束时间'),
            'use_end_time' => Yii::t('app', '使用结束时间'),
            'rate' => Yii::t('app', '体验金金额'),
            'status' => Yii::t('app', '状态'),
            'create_at' => Yii::t('app', '创建时间'),
            'update_at' => Yii::t('app', '更新时间'),
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
            self::STATUS_USE => "使用中",
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
