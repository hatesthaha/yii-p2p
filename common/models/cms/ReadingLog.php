<?php

namespace common\models\cms;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "reading_log".
 *
 * @property integer $id
 * @property string $aid
 * @property integer $uid
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class ReadingLog extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DEL =  0;
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
        return 'reading_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid', 'uid', 'status', 'create_at', 'update_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'aid' => '文章id',
            'uid' => ' 用户id',
            'status' => 'Status',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
