<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "site_sina_balance".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $identity_id
 * @property string $phone
 * @property string $user_name
 * @property string $bank_card
 * @property double $site_balance
 * @property double $sina_available_balance
 * @property double $user_earnings
 * @property double $sina_balance
 * @property double $sina_bonus
 * @property double $sina_bonus_day
 * @property double $sina_bonus_month
 * @property double $sina_bonus_sum
 * @property string $create_time
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class SiteSinaBalance extends ActiveRecord
{

    const STATUS_SUS = 1;
    const STATUS_ERR = -1;

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
        return 'site_sina_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['site_balance', 'sina_available_balance', 'user_earnings', 'sina_balance', 'sina_bonus_day', 'sina_bonus_month', 'sina_bonus_sum'], 'number'],
            [['identity_id', 'phone', 'user_name', 'bank_card', 'create_time','sina_bonus'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'identity_id' => '新浪用户标识',
            'phone' => '用户手机号',
            'user_name' => '用户名',
            'bank_card' => '用户银行卡',
            'site_balance' => '网站余额',
            'sina_available_balance' => '新浪可用余额',
            'user_earnings' => '用户的收益差',
            'sina_balance' => '新浪余额',
            'sina_bonus' => '新浪货币基金',
            'sina_bonus_day' => '新浪货币基金每日收益',
            'sina_bonus_month' => '新浪货币基金每月收益',
            'sina_bonus_sum' => '新浪货币基金总数',
            'create_time' => '创建时间',
            'status' => 'Status',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
