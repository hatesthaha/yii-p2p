<?php

namespace common\models\base\cms;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "cms_about".
 *
 * @property integer $id
 * @property string $name
 * @property integer $pai
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class About extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    private $_statusLabel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_about';
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
            [['pai', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 64]
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
            'pai' => Yii::t('app', '排序'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', '状态'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }

    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Yii::t('app', 'STATUS_INACTIVE'),
            self::STATUS_DELETED => Yii::t('app', 'STATUS_DELETED'),
        ];
    }
}
