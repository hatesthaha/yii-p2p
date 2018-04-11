<?php

namespace common\models\base\activity;

use Yii;

/**
 * This is the model class for table "checkin_log".
 *
 * @property string $id
 * @property string $member_id
 * @property string $raise_id
 * @property integer $checkin_at
 */
class CheckinLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checkin_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'raise_id', 'checkin_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'raise_id' => 'Raise ID',
            'checkin_at' => 'Checkin At',
        ];
    }
}
