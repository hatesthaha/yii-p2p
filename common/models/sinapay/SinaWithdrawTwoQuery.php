<?php

namespace common\models\sinapay;

/**
 * This is the ActiveQuery class for [[SinaWithdrawTwo]].
 *
 * @see SinaWithdrawTwo
 */
class SinaWithdrawTwoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SinaWithdrawTwo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SinaWithdrawTwo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}