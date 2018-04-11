<?php

namespace common\models\base\ucenter;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "ucenter_cat".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class Cat extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;
    private $_statusLabel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ucenter_cat';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 64]
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
            self::STATUS_ACTIVE => "正常",
            self::STATUS_DELETED => "删除",
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
