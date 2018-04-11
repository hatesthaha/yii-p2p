<?php

namespace common\models\base\site;
use yii\db\ActiveQuery;
/**
 * This is the ActiveQuery class for [[VerifyCode]].
 *
 * @see VerifyCode
 */
class VerifyCodeQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VerifyCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VerifyCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}