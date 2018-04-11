<?php

namespace common\models\post;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "send_msg_all".
 *
 * @property integer $id
 * @property string $phone
 * @property string $templateid
 * @property string $send_msg
 * @property integer $status
 * @property string $return_msg
 * @property integer $create_at
 * @property integer $update_at
 */
class SendMsgAll extends ActiveRecord
{

    //用户签到
    const STATUS_ACTIVE = 1;
    //用户获得了体验金
    const STATUS_FINISH = 2;
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
        return 'send_msg_all';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'create_at', 'update_at'], 'integer'],
            [['phone', 'templateid', 'send_msg', 'return_msg'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => '发送手机号',
            'templateid' => '短信模板',
            'send_msg' => '发送的内容',
            'status' => 'Status',
            'return_msg' => 'Return Msg',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     * @return SendMsgAllQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SendMsgAllQuery(get_called_class());
    }
}
