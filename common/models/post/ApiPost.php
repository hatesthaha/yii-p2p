<?php

namespace common\models\post;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "api_post".
 *
 * @property integer $id
 * @property string $action
 * @property string $post_data
 * @property string $post_ip
 * @property integer $create_at
 * @property integer $update_at
 */
class ApiPost extends ActiveRecord
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
        return 'api_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_data','post_version','post_from'], 'string'],
            [['create_at', 'update_at'], 'integer'],
            [['action', 'post_ip','post_time','post_area'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => '动作',
            'post_data' => '提交的数据',
            'post_version' => '提交的版本号',
            'post_from' => '提交数据来源',
            'post_ip' => '提交的ip',
            'post_time' => '提交时间',
            'post_area' => '地区',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
