<?php

namespace common\models\base\asset;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "clerk_log".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $clerk_id
 * @property string $behav
 * @property double $step
 * @property string $remark
 * @property integer $create_at
 * @property integer $update_at
 */
class ClerkLog extends ActiveRecord
{
    const CLERK_BEHAV_ONE = '收入';
    const CLERK_BEHAV_TWO = '支出';

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
        return 'clerk_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'clerk_id', 'behav', 'step', 'remark'], 'required'],
            [['member_id', 'clerk_id', 'create_at', 'update_at'], 'integer'],
            [['step'], 'number'],
            [['behav'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'clerk_id' => '职员id',
            'behav' => '行为：支出，收入',
            'step' => '金额',
            'remark' => '备注',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
