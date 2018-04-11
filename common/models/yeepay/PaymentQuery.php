<?php

namespace common\models\yeepay;

/**
 * This is the ActiveQuery class for [[Payment]].
 *
 * @see Payment
 */
class PaymentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Payment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Payment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}