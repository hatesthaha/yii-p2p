<?php

namespace common\models\yeepay;

/**
 * This is the ActiveQuery class for [[Bindbankcard]].
 *
 * @see Bindbankcard
 */
class BindbankcardQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Bindbankcard[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Bindbankcard|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}