<?php

namespace common\models\base\asset;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "task_log".
 *
 * @property integer $id
 * @property string $remark
 * @property string $url
 * @property integer $create_at
 * @property integer $update_at
 */
class TaskLog extends \yii\db\ActiveRecord
{
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
        return 'task_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['remark', 'url'], 'required'],
            [['create_at', 'update_at'], 'integer'],
            [['remark'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'remark' => '计划任务错误描述',
            'url' => '文件名\\方法名',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
