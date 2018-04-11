<?php

namespace common\models\post;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\base\asset\Info;
/**
 * This is the model class for table "sign_in".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $sign_in_time
 * @property double $sign_in_money
 * @property string $sign_in_ip
 * @property string $sign_in_from
 * @property integer $create_at
 * @property integer $update_at
 */
class SignIn extends ActiveRecord
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
        return 'sign_in';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sign_in_time', 'status', 'create_at', 'update_at'], 'integer'],
            [['sign_in_money'], 'number'],
            [['sign_in_ip', 'sign_in_from'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户id',
            'sign_in_time' => '签到时间',
            'sign_in_money' => '签到奖励金额',
            'sign_in_ip' => '签到ip',
            'sign_in_from' => '签到来源',
            'status' => '状态',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(Info::className(), ['member_id' => 'uid']);
    }
}
