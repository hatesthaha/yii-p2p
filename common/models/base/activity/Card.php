<?php

namespace common\models\base\activity;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "activity_card".
 *
 * @property integer $id
 * @property string $title
 * @property integer $use_start_at
 * @property integer $use_out_at
 * @property integer $validity_time
 * @property double $rate
 * @property integer $created_at
 * @property integer $updated_at
 */
class Card extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;
    private $_statusLabel;
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),

        ];
    }
    /*
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [[ 'validity_time'], 'integer'],
           // [['rate'], 'number'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '标题'),
            'use_start_at' => Yii::t('app', '有效期开始时间'),
            'use_out_at' => Yii::t('app', '有效期结束时间'),
            'validity_time' => Yii::t('app', '增息卡有效时长天数'),
            'rate' => Yii::t('app', '增息利率'),
            'card' => Yii::t('app', '状态'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
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
            self::STATUS_ACTIVE=>"正常",
            self::STATUS_DELETED => "删除",
        ];
    }

}
