<?php

namespace common\models\base\site;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "idcard_log".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $idcard
 * @property integer $status
 * @property string $address
 * @property string $sex
 * @property string $birthday
 * @property string $remark
 * @property integer $create_at
 * @property integer $update_at
 */
class IdcardLog extends ActiveRecord
{
    /**
     * @return array
     */
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
        return 'idcard_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['name', 'idcard', 'birthday'], 'string', 'max' => 25],
            [['address', 'remark'], 'string', 'max' => 255],
            [['sex'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'name' => '姓名',
            'idcard' => '身份证',
            'status' => 'Status',
            'address' => 'Address',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'remark' => '备注',
            'create_at' => '创建时间',
            'update_at' => 'Update At',
        ];
    }
}
