<?php

namespace common\models\base\activity;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "user_recommend".
 *
 * @property integer $id
 * @property integer $owner_uid
 * @property integer $level
 * @property integer $sender_uid
 * @property string $sender_phone
 * @property string $sender_real_name
 * @property integer $sender_register_time
 * @property integer $sender_parent_id
 * @property double $red_packet
 * @property integer $is_grant
 * @property integer $create_at
 * @property integer $update_at
 */
class UserRecommend extends ActiveRecord
{
    //已经发放成功--领取用户达标
    const GRANT_TRUE = 1;
    //还未发放--领取用户没有达标
    const GRANT_FALSE = 0;
    //删除
    const GRANT_DEL = -1;
    //用户注册产生关系
    const GRANT_RECOMMEND = -2;

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
        return 'user_recommend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_uid', 'level', 'sender_uid', 'sender_register_time', 'sender_parent_id', 'is_grant', 'create_at', 'update_at','demand_days'], 'integer'],
            [['red_packet','demand_money'], 'number'],
            [['sender_phone', 'sender_real_name','red_packet_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_uid' => '根id',
            'level' => '推荐等级',
            'sender_uid' => '发红包者id',
            'sender_phone' => '注册手机号',
            'sender_real_name' => '真实姓名',
            'sender_register_time' => '注册时间',
            'sender_parent_id' => '父级id',
            'red_packet' => '红包金额',
            'is_grant' => '是否已经发放',
            'demand_money' => '规则要求的钱数',
            'demand_days' => '规则要求的天数',
            'red_packet_name' => '红包名称',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
