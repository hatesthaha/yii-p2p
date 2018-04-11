<?php

namespace common\models\lianlian;

/**
 * This is the ActiveQuery class for [[payLL]].
 *
 * @see payLL
 */
class LianlianPayQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return payLL[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return payLL|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}