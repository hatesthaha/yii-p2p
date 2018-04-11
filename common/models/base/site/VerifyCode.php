<?php

namespace common\models\base\site;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "site_verify_code".
 *
 * @property integer $id
 * @property string $code
 * @property string $field
 * @property integer $type
 * @property integer $b_time
 * @property integer $e_time
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class VerifyCode extends ActiveRecord
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
        return 'site_verify_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'b_time', 'e_time', 'status', 'create_at', 'update_at'], 'integer'],
//            [['code', 'field'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'field' => 'Field',
            'type' => 'Type',
            'b_time' => 'B Time',
            'e_time' => 'E Time',
            'status' => 'Status',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     * @return VerifyCodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VerifyCodeQuery(get_called_class());
    }
}
