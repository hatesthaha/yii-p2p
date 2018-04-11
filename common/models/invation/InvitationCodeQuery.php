<?php

namespace common\models\invation;

/**
 * This is the ActiveQuery class for [[InvitationCode]].
 *
 * @see InvitationCode
 */
class InvitationCodeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return InvitationCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return InvitationCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}