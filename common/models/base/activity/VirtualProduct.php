<?php

namespace common\models\base\activity;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "virtual_product".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property string $phone
 * @property double $money
 * @property integer $create_at
 * @property integer $update-at
 */
class VirtualProduct extends ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'virtual_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'create_at', 'update_at'], 'integer'],
            [['money'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'name' => 'Name',
            'phone' => 'Phone',
            'money' => 'Money',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
