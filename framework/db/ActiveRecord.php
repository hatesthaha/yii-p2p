<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 */


namespace framework\db;


use common\aa;
use yii\behaviors\TimestampBehavior;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
            ],
        ];
    }
}