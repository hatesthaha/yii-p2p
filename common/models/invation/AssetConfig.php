<?php

namespace common\models\invation;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "asset_config".
 *
 * @property integer $id
 * @property integer $deposit_num
 * @property double $deposit_min
 * @property double $deposit_max
 * @property integer $invest_num
 * @property double $invest_min
 * @property double $invest_max
 * @property integer $withdraw_num
 * @property double $withdraw_min
 * @property double $withdraw_max
 * @property integer $ransom_num
 * @property double $ransom_min
 * @property double $ransom_max
 * @property integer $create_at
 * @property integer $update_at
 */
class AssetConfig extends ActiveRecord
{
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
        return 'asset_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deposit_num', 'invest_num', 'withdraw_num', 'ransom_num','deposit_time','invest_time','ransom_time','withdraw_time'], 'integer'],
            [['deposit_min', 'deposit_max', 'invest_min', 'invest_max', 'withdraw_min', 'withdraw_max', 'ransom_min', 'ransom_max'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deposit_num' => '充值次数',
            'deposit_min' => '充值最小金额',
            'deposit_max' => '充值最大金额',
            'deposit_time' => '充值等待最大时间',
            'invest_num' => '投资次数',
            'invest_min' => '投资最小金额',
            'invest_max' => '投资最大金额',
            'invest_time' => '投资等待最大时间',
            'ransom_num' => '赎回次数',
            'ransom_min' => '赎回最小金额',
            'ransom_max' => '赎回最大金额',
            'ransom_time' => '赎回等待最大时间',
            'withdraw_num' => '提现次数',
            'withdraw_min' => '提现最小金额',
            'withdraw_max' => '提现最大金额',
            'withdraw_time' => '提现等待最大时间',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
