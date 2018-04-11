<?php

namespace www\controllers;

use common\models\base\cms\About;
use Yii;
use yii\web\Controller;
use yii\base\ErrorException;
use common\models\cms\Category;
use backend\models\Article;

class HelpController extends Controller
{
    public $layout='main';

    public function actionIndex()
    {
        $left = About::find()->asArray()->all();
        return $this->render('index',['left'=>$left]);
    }

    public function actionNews()
    {
        if($_GET['id'])
        {
        	$id = $_GET['id'];
        }
        $left = About::find()->asArray()->all();
        $category_id = \common\models\cms\Article::findOne(['id'=>$id])->category_id;
        $category_name = \backend\models\Category::findOne(['id'=>$category_id])->title;
        $infos = \common\models\cms\Article::findOne(['id'=>$id]);
        
        return $this->render('index',compact("infos","category_name","left"));
    }
    
    public function actionNews_second()
    {
    	if($_GET['id'])
    	{
    		$id = $_GET['id'];
    	}
    	if($_GET['pid'])
    	{
    		$pid = $_GET['pid'];
    	}
    	$category_name = \backend\models\Category::findOne(['id'=>$pid])->title;
    	$second_name = \backend\models\Category::findOne(['id'=>$id])->title;
    	$infos = \common\models\cms\Article::find()->where(['category_id'=>$id])->asArray()->all();
    
    	return $this->render('news_second',compact("infos","category_name","second_name"));
    }
}