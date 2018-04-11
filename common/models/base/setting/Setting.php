<?php

namespace common\models\base\setting;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $code
 * @property string $type
 * @property string $store_range
 * @property string $store_dir
 * @property string $value
 * @property integer $sort_order
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort_order'], 'integer'],
            [['code', 'type'], 'required'],
            [['value'], 'string'],
            [['code', 'type'], 'string', 'max' => 32],
            [['store_range', 'store_dir'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'code' => Yii::t('app', 'Code'),
            'type' => Yii::t('app', 'Type'),
            'store_range' => Yii::t('app', 'Store Range'),
            'store_dir' => Yii::t('app', 'Store Dir'),
            'value' => Yii::t('app', 'Value'),
            'sort_order' => Yii::t('app', 'Sort Order'),
        ];
    }
    public static function get($code)
    {
        if(!$code) return ;
        $setting = Setting::find()->where(['code' => $code])->one();

        if($setting)
            return $setting->value;
        else
            return ;
    }
}
