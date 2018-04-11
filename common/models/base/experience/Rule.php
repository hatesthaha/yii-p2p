<?php

namespace common\models\base\experience;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "experience_rule".
 *
 * @property integer $id
 * @property double $money
 * @property string $start_at
 * @property string $end_at
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class Rule extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;
    private $_statusLabel;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'experience_rule';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['money'], 'number'],

           // [['start_at', 'end_at'], 'string', 'max' => 64],
          //  [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'money' => Yii::t('app', '金额'),
            'start_at' => Yii::t('app', '开始时间'),
            'end_at' => Yii::t('app', '结束时间'),
            'time' => Yii::t('app', '天数'),
            'title' => Yii::t('app', '体验金名称'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', '状态'),
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
            self::STATUS_ACTIVE=>"启用",
            self::STATUS_DELETED => "未启用",
        ];
    }
}
