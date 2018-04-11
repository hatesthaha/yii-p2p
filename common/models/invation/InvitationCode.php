<?php

namespace common\models\invation;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "invitation_code".
 *
 * @property integer $id
 * @property integer $code
 * @property integer $use_id
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class InvitationCode extends ActiveRecord
{

    //删除
    const STATUS_DELETED = -1;
    //邀请码可用
    const STATUS_ACTIVE = 0;
    //邀请码已经被使用
    CONST STATUS_USED = 1;


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
        return 'invitation_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'use_id', 'status', 'create_at', 'update_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '邀请码',
            'use_id' => '使用者',
            'status' => 'Status',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
