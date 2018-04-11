<?php

namespace common\models\base\site;

use Yii;

/**
 * This is the model class for table "site_common".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property integer $pid
 * @property integer $create_at
 * @property integer $update_at
 */
class Common extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_common';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'string'],
            [['pid', 'create_at', 'update_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
            'pid' => Yii::t('app', 'Pid'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
        ];
    }

}
