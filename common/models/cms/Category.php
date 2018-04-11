<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 */


namespace common\models\cms;


use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class Category extends \common\models\base\cms\Category
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param string $intro
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param int $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $dataFormat
     * @return int
     */
    public function getCreateAt($dataFormat = 'Y-m-d H:i:s')
    {
        return date($dataFormat, $this->create_at);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['category_id' => 'id']);
    }
}