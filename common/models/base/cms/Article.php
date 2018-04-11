<?php

namespace common\models\base\cms;

use Yii;

/**
 * This is the model class for table "{{%cms_article}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property string $title
 * @property string $intro
 * @property string $content
 * @property integer $create_at
 * @property integer $update_at
 * @property string $logo
 * @property Category $category
 * @property UcenterUser $user
 */
class Article extends \framework\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'create_at', 'update_at'], 'integer'],
            [['content'], 'string'],
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
            'user_id' => '用户ID',
            'category_id' => 'Category ID',
            'title' => 'Title',
            'intro' => 'Intro',
            'content' => 'Content',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UcenterUser::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \common\querys\cms\ArticleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\querys\cms\ArticleQuery(get_called_class());
    }
}
