<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_notify".
 *
 * @property integer $id
 * @property string $notify_type
 * @property string $notify_id
 * @property string $_input_charset
 * @property string $notify_time
 * @property string $sign
 * @property string $sign_type
 * @property string $version
 * @property string $memo
 * @property string $error_code
 * @property string $error_message
 * @property string $notify_data
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaNotify extends ActiveRecord
{

    //删除
    const STATUS_DELETED = -1;
    //错误
    const STATUS_ERROR = 0;
    //成功
    CONST STATUS_SUCCESS = 1;


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
        return 'sina_notify';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['memo', 'error_message', 'notify_data'], 'string'],
            [['create_at', 'update_at'], 'integer'],
            [['notify_type', 'notify_id', 'sign'], 'string', 'max' => 255],
            [['_input_charset', 'notify_time', 'sign_type', 'version', 'error_code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'notify_type' => '通知类型',
            'notify_id' => '通知编码',
            '_input_charset' => '参数编码字符集',
            'notify_time' => '通知时间',
            'sign' => 'Sign',
            'sign_type' => 'Sign Type',
            'version' => 'Version',
            'memo' => '备注',
            'error_code' => 'Error Code',
            'error_message' => '错误信息',
            'notify_data' => 'Notify Data',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
