<?php

namespace common\models\base\ucenter;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\UcenterMember;
/**
 * This is the model class for table "ucenter_catmiddle".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $cid
 * @property string $created_at
 * @property string $updated_at
 */
class Catmiddle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ucenter_catmiddle';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'cid'], 'integer'],
           // [['created_at', 'updated_at'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'cid' => Yii::t('app', 'Cid'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasMany(UcenterMember::className(), ['id' => 'uid']);
    }
}
