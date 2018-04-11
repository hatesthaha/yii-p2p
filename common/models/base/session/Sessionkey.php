<?php

namespace common\models\base\session;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "app_sessionkey".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $sessionkey
 * @property integer $status
 * @property integer $start_time
 * @property integer $end_time
 */
class Sessionkey extends ActiveRecord
{

    //用户注销登陆
    const STATUS_DELETED = -1;
    //用户处于登陆中
    CONST STATUS_CONFIRM = 1;
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['start_time', 'end_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['end_time'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_sessionkey';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'start_time', 'end_time'], 'integer'],
            [['sessionkey'], 'string', 'max' => 100],
            [['sessionkey'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'sessionkey' => 'Sessionkey',
            'status' => 'Status',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
        ];
    }
}
