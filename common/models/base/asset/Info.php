<?php

namespace common\models\base\asset;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\UcenterMember;
/**
 * This is the model class for table "asset_info".
 *
 * @property integer $member_id
 * @property string $bank_card
 * @property string $bank_card_phone
 * @property double $balance
 * @property double $freeze
 * @property integer $create_at
 * @property integer $update_at
 *
 * @property UcenterMember $member
 */
class Info extends ActiveRecord
{
    const GO_ONE = 1;//充值中（正在进行）
    const GO_TWO = 2;//投资中（正在进行）
    const GO_THREE = 3;//赎回中（正在进行）
    const GO_FOUR = 4;//提现中（正在进行）
	//申购金额
	public $money;
    //新浪余额
    public $sinamoney;
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
        return 'asset_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'create_at', 'update_at'], 'integer'],
            [['balance', 'freeze'], 'number'],
        	['money','validateMoney'],
            [['bank_card'], 'string', 'max' => 255],
            [['bank_card_phone'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => Yii::t('app', '会员用户'),
            'bank_card' => Yii::t('app', '银行卡'),
            'bank_card_phone' => Yii::t('app', '银行卡手机'),
            'balance' => Yii::t('app', '余额'),
            'freeze' => Yii::t('app', '冻结资金'),
            'create_at' => Yii::t('app', '创建时间'),
            'update_at' => Yii::t('app', '更新时间'),
            'invest' => Yii::t('app', '再投资金'),
            'total_invest'=> Yii::t('app', '累计投资'),
            'profit'=> Yii::t('app', '可用收益'),
            'profit_freeze'=> Yii::t('app', '冻结收益'),
            'total_revenue'=> Yii::t('app', '累计收益'),
        ];
    }

    public function validateMoney($attribute,$params)
    {
    	if($attribute < $this->balance)
    		$this->addError($attribute,'账户余额不足');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'member_id']);
    }
}
