<?php

namespace common\querys\cms;

/**
 * This is the ActiveQuery class for [[\common\models\base\cms\Category]].
 *
 * @see \common\models\base\cms\Category
 */
class CategoryQuery extends \framework\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \common\models\base\cms\Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\base\cms\Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}