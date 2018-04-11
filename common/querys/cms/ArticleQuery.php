<?php

namespace common\querys\cms;

/**
 * This is the ActiveQuery class for [[\common\models\base\cms\Article]].
 *
 * @see \common\models\base\cms\Article
 */
class ArticleQuery extends \framework\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \common\models\base\cms\Article[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\base\cms\Article|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}