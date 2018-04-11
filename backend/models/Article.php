<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use backend\models\Category;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cms_article".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property string $title
 * @property string $logo
 * @property string $intro
 * @property string $content
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $status
 * @property CmsCategory $category
 * @property UcenterUser $user
 */
class Article extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;
    private $_statusLabel;
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
                'updatedAtAttribute' => 'update_at',
                //'value' => new Expression('NOW()'),
                //'value'=>$this->timeTemp(),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id','status'], 'integer'],
            [['content','logo'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['intro'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [
            self::STATUS_INACTIVE => Yii::t('app', '未发布'),
            self::STATUS_ACTIVE => Yii::t('app', '已发布'),
            self::STATUS_DELETED => Yii::t('app', '删除'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '发布人',
            'category_id' => '文章分类',
            'title' => '标题',
            'intro' => '简介',
            'content' => '内容',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'status'=>'发布状态',
            'logo' => '缩略图'
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
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public static function getArrayCategory()
    {
        return ArrayHelper::map(Category::find()->all(), 'id', 'title');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
