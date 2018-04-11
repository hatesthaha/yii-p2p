<?php

namespace common\models\base\fund;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "fund_income".
 *
 * @property integer $id
 * @property integer $member_id
 * @property double $money
 * @property double $smoney
 * @property double $rate
 * @property string $created_at
 * @property string $updated_at
 */
class Income extends \yii\db\ActiveRecord
{
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
    public static function tableName()
    {
        return 'fund_income';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'member_id', 'money'], 'required'],
            [[ 'member_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'money' => Yii::t('app', 'Money'),
            'smoney' => Yii::t('app', 'Smoney'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
