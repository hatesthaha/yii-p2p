<?php

namespace common\models\base\site;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "operating".
 *
 * @property integer $id
 * @property string $username
 * @property string $step
 * @property string $created_at
 * @property string $updated_at
 */
class Operating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operating';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'step'], 'required'],
            [['username', 'step'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', '用户名'),
            'step' => Yii::t('app', '操作'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    public static function addlog($username,$step){
        $model = new Operating();
        $model->username = $username;
        $model->step =$step;
        $model->save();
    }
}
