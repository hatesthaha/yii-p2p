<?php

namespace common\models\base\asset;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "trade_log".
 *
 * @property integer $id
 * @property double $t_recharge
 * @property double $t_invest
 * @property double $t_redeem
 * @property double $t_withdraw
 * @property double $t_profit
 * @property double $t_gold
 * @property double $t_red
 * @property integer $create_at
 * @property integer $t_date
 */
class TradeLog extends \yii\db\ActiveRecord
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
        return 'trade_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['t_recharge', 't_invest', 't_redeem', 't_withdraw', 't_profit', 't_gold', 't_red'], 'number'],
            [['create_at','update_at', 't_date'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            't_recharge' => '一天的总充值金额',
            't_invest' => '一天总的投资金额',
            't_redeem' => '一天总的赎回金额',
            't_withdraw' => '一天总的提现金额',
            't_profit' => '一天总在投金额的收益',
            't_gold' => '一天总的体验金的收益',
            't_red' => '一天总的发放的红包金额',
            'create_at' => 'Create At',
            't_date' => '日期',
        ];
    }
}
