<?php

namespace common\models\base\fund;

use Yii;

/**
 * This is the model class for table "fund_common".
 *
 * @property integer $id
 * @property double $invest_sum
 * @property integer $invest_people
 * @property integer $invest_times
 * @property integer $create_at
 * @property integer $update_at
 */
class Common extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_common';
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
            'id' => 'ID',
            'invest_sum' => 'Invest Sum',
            'invest_people' => 'Invest People',
            'invest_times' => 'Invest Times',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
