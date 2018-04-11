<?php

namespace common\models\base\fund;

use Yii;

/**
 * This is the model class for table "fund_daycommon".
 *
 * @property integer $id
 * @property double $invest_sum
 * @property integer $invest_people
 * @property integer $invest_times
 * @property integer $create_at
 * @property integer $update_at
 */
class Daycommon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_daycommon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invest_sum'], 'number'],
            [['invest_people', 'invest_times', 'create_at', 'update_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'invest_sum' => Yii::t('app', 'Invest Sum'),
            'invest_people' => Yii::t('app', 'Invest People'),
            'invest_times' => Yii::t('app', 'Invest Times'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
        ];
    }
}
