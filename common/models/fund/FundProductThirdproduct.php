<?php

namespace common\models\fund;

use Yii;

/**
 * This is the model class for table "fund_product_thirdproduct".
 *
 * @property string $id
 * @property integer $thirdproduct_id
 * @property integer $product_id
 *
 * @property FundThirdproduct $thirdproduct
 * @property FundProduct $product
 */
class FundProductThirdproduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_product_thirdproduct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['thirdproduct_id', 'product_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thirdproduct_id' => 'Thirdproduct ID',
            'product_id' => 'Product ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThirdproduct()
    {
        return $this->hasOne(FundThirdproduct::className(), ['id' => 'thirdproduct_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(FundProduct::className(), ['id' => 'product_id']);
    }
}
