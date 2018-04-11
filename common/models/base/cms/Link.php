<?php

namespace common\models\base\cms;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\base\cms\Cat;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "cms_link".
 *
 * @property integer $id
 * @property integer $cat_id
 * @property string $intro
 * @property string $bannar
 * @property string $link
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class Link extends \yii\db\ActiveRecord
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
        return 'cms_link';
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
            [['cat_id', 'status'], 'required'],
            [['cat_id', 'status'], 'integer'],
            [['intro', 'link'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 64],
            [['bannar'], 'file', 'maxFiles' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cat_id' => Yii::t('app', '分类'),
            'intro' => Yii::t('app', '简介'),
            'bannar' => Yii::t('app', '图片'),
            'link' => Yii::t('app', '链接地址'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
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
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Cat::className(), ['id' => 'cat_id']);
    }

    public static function getArrayCats(){
        return ArrayHelper::map(Cat::find()->all(), 'id', 'name');
    }
}
