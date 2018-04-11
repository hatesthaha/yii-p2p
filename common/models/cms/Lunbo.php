<?php

namespace common\models\cms;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "lunbo".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $status
 * @property string $order
 * @property integer $create_at
 * @property integer $update_at
 */
class Lunbo extends ActiveRecord
{

    const STATUS_DELETED = -1;

    CONST STATUS_SUCCESS = 1;
    //轮播类型图片
    CONST TYPE_LUNBO = 1;
    //启动页类型的图片
    CONST TYPE_QIDONG = 2;

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
        return 'lunbo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['create_at', 'update_at', 'order','type'], 'integer'],
            [['title', 'url', 'status','info','event_link','share_link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '图片标题',
            'url' => '图片链接',
            'status' => '状态',
            'type' => '图片类型',
            'order' => '图片排序权重',
            'info' => '图片简介',
            'content' => '图片内容',
            'share_link' => '分享链接',
            'event_link' => '活动链接',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     * @return LuoboQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LunboQuery(get_called_class());
    }

    public static function status_labels()
    {
        return [
            self::STATUS_DELETED=>"不启用",
            self::STATUS_SUCCESS => "启用",
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
    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [
            self::STATUS_DELETED => Yii::t('app', '不启用'),
            self::STATUS_SUCCESS => Yii::t('app', '启用'),
        ];
    }

    private $_typeLabel;

    public static function type_labels()
    {
        return [
            self::TYPE_QIDONG=>"启动页图片",
            self::TYPE_LUNBO => "轮播图片",
        ];
    }
    public function getTypeLabel()
    {
        if ($this->_statusLabel === null) {
            $types = self::getArrayType();
            $this->_typeLabel = $types[$this->type];
        }
        return $this->_typeLabel;
    }

    public static function getArrayType()
    {
        return [
            self::TYPE_LUNBO => Yii::t('app', '轮播图片'),
            self::TYPE_QIDONG=> Yii::t('app', '启动页图片'),
        ];
    }
}
