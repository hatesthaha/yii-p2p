<?php

namespace common\models\base\cms;

use Yii;

/**
 * This is the model class for table "{{%cms_category}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $intro
 * @property integer $parent_id
 * @property integer $create_at
 * @property integer $update_at
 *
 * @property Article[] $articles
 */
class Category extends \framework\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'create_at', 'update_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['intro'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'intro' => 'Intro',
            'parent_id' => 'Parent ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\querys\cms\CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\querys\cms\CategoryQuery(get_called_class());
    }
}
