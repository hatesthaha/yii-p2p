<?php

namespace common\models\base\ucenter;

use Yii;
use common\models\UcenterMember;
/**
 * This is the model class for table "ucenter_log".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $user_id
 * @property string $login_ip
 * @property integer $login_time
 * @property string $login_area
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 *
 * @property UcenterMember $member
 * @property UcenterUser $user
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ucenter_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'user_id', 'login_time', 'status', 'create_at', 'update_at'], 'integer'],
            [['login_ip', 'login_area'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', '用户名'),
            'user_id' => Yii::t('app', 'User ID'),
            'login_ip' => Yii::t('app', '登陆ip'),
            'login_time' => Yii::t('app', '登陆时间'),
            'login_area' => Yii::t('app', '登陆地区'),
            'status' => Yii::t('app', 'Status'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UcenterUser::className(), ['id' => 'user_id']);
    }
}
