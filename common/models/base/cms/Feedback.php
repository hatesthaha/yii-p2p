<?php

namespace common\models\base\cms;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\UcenterMember;
/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $feedback
 * @property string $created_at
 * @property string $updated_at
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'feedback'], 'required'],
            [['uid'], 'integer'],
            [['feedback'], 'string'],
            [['created_at', 'updated_at'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', '用户'),
            'feedback' => Yii::t('app', '反馈意见'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'uid']);
    }
}
