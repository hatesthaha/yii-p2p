<?php

namespace common\models\base\activity;

use Yii;

/**
 * This is the model class for table "activity_red_packets".
 *
 * @property integer $id
 * @property integer $start_at
 * @property integer $end_at
 * @property double $amount
 * @property double $each_max
 * @property double $each_min
 * @property integer $create_at
 * @property integer $update_at
 */
class RedPackets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_red_packets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_at', 'end_at', 'create_at', 'update_at'], 'integer'],
            [['amount', 'each_max', 'each_min'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'End At'),
            'amount' => Yii::t('app', 'Amount'),
            'each_max' => Yii::t('app', 'Each Max'),
            'each_min' => Yii::t('app', 'Each Min'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
        ];
    }
}
