<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 */


namespace tests\common\models;


use common\models\base\cms\Article;
use common\models\base\cms\Category;
use common\querys\cms\CategoryQuery;
use Exception;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function createTest()
    {
        $category = new \common\models\cms\Category();
        $category->setTitle("tile4");
        $category->setIntro("intro4");
        $category->setParentId(0);
        $category->on(Category::EVENT_BEFORE_INSERT, function ($event) use ($category) {
            $category->setTitle("aaa");
        });
//        $category->detachBehavior('timestampBehavior');
        $category->save();
    }

    /** @test */
    public function readTest()
    {
        $connection = \Yii::$app->db;
        $connection->transaction(function () use ($connection) {

            $category = new \common\models\cms\Category();
            $category->setTitle("tile4");
            $category->setParentId(0);
            $category->save();

            $category1 = new \common\models\cms\Category();
            $category1->setTitle("tile4");
            $category1->setParentId(0);
            $category1->save();
            throw new ForbiddenHttpException;

        });
    }
}
