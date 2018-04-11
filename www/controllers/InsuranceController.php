<?php
/**
 * @author: liushaohua
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 * @time 2015年7月6日 15:15:54
 */

namespace www\controllers;

use Yii;
use common\models\cms\Article;
use common\models\cms\Category;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\base\ErrorException;

/**
 * Site controller
 */
class InsuranceController extends Controller
{
	public $layout='main';
    
    /* 安全保障*/
    public function actionPlan()
    {
    	
	    	$category_id = Category::findOne(['title'=>'安全保障','status'=>1])->id;
    	try
    	{
	    	$infos = Article::find()->where(['category_id'=>$category_id,'status'=>1])->all();
    	}
    	catch (ErrorException $e)
    	{
    		$infos = array();
    	}
    	
    	return $this->render('plan', compact("infos"));
    }
    
}
