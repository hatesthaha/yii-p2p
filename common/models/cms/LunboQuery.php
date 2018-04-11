<?php

namespace common\models\cms;

/**
 * This is the ActiveQuery class for [[Lunbo]].
 *
 * @see Lunbo
 */
class LunboQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Lunbo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Lunbo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}