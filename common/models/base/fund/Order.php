<?php

namespace common\models\base\fund;

use Yii;
use common\models\base\activity\RaiseCard;
use common\models\UcenterMember;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\base\fund\Product;
/**
 * This is the model class for table "fund_orders".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $product_id
 * @property double $money
 * @property integer $status
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $create_at
 * @property integer $update_at
 *
 * @property ActivityRaiseCard[] $activityRaiseCards
 * @property FundProduct $product
 * @property UcenterMember $member
 */
class Order extends ActiveRecord
{
    const STATUS_ACTIVE = 1;//订单生效
    const STATUS_DELETE = 2;//订单被删除或订单内的钱全部被赎回

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
        return 'fund_orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'product_id', 'status', 'start_at', 'end_at', 'create_at', 'update_at'], 'integer'],
            [['money'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户名',
            'product_id' => '项目名',
            'money' => '金额',
            'status' => '状态',
            'start_at' => '开始时间',
            'end_at' => 'End At',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityRaiseCards()
    {
        return $this->hasMany(RaiseCard::className(), ['fund_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'member_id']);
    }
    public static function weekorder(){
        return $weekorder = self::find()
            ->select('sum(money) as smoney')
            ->andWhere(['>=', 'create_at', time() - 7 * 24 * 3600])
            ->andWhere(['status'=>self::STATUS_ACTIVE])
            ->asArray()
            ->one();
    }
}
