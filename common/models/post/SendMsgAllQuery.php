<?php

namespace common\models\post;

/**
 * This is the ActiveQuery class for [[SendMsgAll]].
 *
 * @see SendMsgAll
 */
class SendMsgAllQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SendMsgAll[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SendMsgAll|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}