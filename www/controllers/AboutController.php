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
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use common\models\base\cms\Cat;
use common\models\base\cms\Link;
use yii\base\ErrorException;
use common\models\base\cms\About;
/**
 * Site controller
 */
class AboutController extends Controller
{
	public $layout='main';
	public $defaultAction = 'company';
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [/* 
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

/*     public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    } */
    
	/* 公司介绍  */
    public function actionCompany()
    {
        $left = About::find()->asArray()->all();
        //var_dump($left);
    	try 
    	{
    		$infos = Article::findOne(['title'=>'企业简介','status'=>1]);
    	}
    	catch (ErrorException $e)
    	{
    		$infos = '';
    	}
        return $this->render('company',['infos'=>$infos,'left'=>$left]);
    }
    
    /* 媒体报道 */
    public function actionMedia()
    {
		$left = About::find()->asArray()->all();
		//var_dump($left);
		try
		{
			$infos = Article::findOne(['title'=>'相关证件','status'=>1]);
		}
		catch (ErrorException $e)
		{
			$infos = '';
		}
		return $this->render('media',['infos'=>$infos,'left'=>$left]);
    }
    
    /* 合作伙伴  */
    public function actionPartner()
    {
        $left = About::find()->asArray()->all();
    	try
    	{
    		$cat_id = Cat::findOne(['name'=>'合作伙伴','status'=>1])->id;
    		$infos = Link::find()->where(['cat_id'=>$cat_id,'status'=>1])->all();
    	}
    	catch (ErrorException $e)
    	{
    		$infos = '';
    	}
    	return $this->render('partner',compact("infos",'left'));
    }

    /* 最新动态  */
    public function actionNews()
    {
        $left = About::find()->asArray()->all();
    	try 
    	{
	    	$parent_id = Category::findOne(['title'=>'最新动态','status'=>1])->id;
	    	$query = Article::find()->where(['category_id'=>$parent_id,'status'=>1]);
	    	$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '4']);
	    	$models = $query->offset($pages->offset)
	    	->limit($pages->limit)
	    	->orderBy("id DESC")
	    	->all();
    	}
    	catch (ErrorException $e)
    	{
    		$pages = '';
    		$models = '';
    	}
    	return $this->render('news', [
	    			'models' => $models,
	    			'pages' => $pages,
                    'left'=>$left,
    			]);
    }
    
    /* 担保机构*/
    public function actionGuarantee()
    {
        $left = About::find()->asArray()->all();
    	try
    	{
    		$cat_id = Cat::findOne(['name'=>'担保机构','status'=>1])->id;
    		$infos = Link::find()->where(['cat_id'=>$cat_id,'status'=>1])->all();
    	}
    	catch (ErrorException $e)
    	{
    		$infos = '';
    	}
    	return $this->render('guarantee', [
    			'infos' => $infos,
            'left'=>$left,
    	]);
    }
    
    /**
     * @内容详细页
     */
    public function actionContent()
    {
    	if(isset($_GET['id']))
    	{
    		$id=$_GET['id'];
    	}
    	$model=Article::findOne($id);
    	
    	return $this->render('content',['model'=>$model]);
    }

    /* 加入我们  */
    public function actionJoin()
    {
        $left = About::find()->asArray()->all();
    try
    	{
	    	$parent_id = Category::findOne(['title'=>'加入我们','status'=>1])->id;
	    	$infos = Article::find()->where(['category_id'=>$parent_id,'status'=>1]);
	    	$pages = new Pagination(['totalCount' => $infos->count(), 'pageSize' => '6']);
	    	$models = $infos->offset($pages->offset)
	    	->limit($pages->limit)
	    	->all();
    	}
    	catch (ErrorException $e)
    	{
    		$infos = '';
    		$pages = '';
    		$models = '';
    	}
    	return $this->render('join',compact("infos","models","pages",'left'));
    }

    /* 联系我们  */
    public function actionContact()
    {
        $left = About::find()->asArray()->all();
    	try
    	{
    		$infos = Article::findOne(['title'=>'联系我们','status'=>1]);
    	}
    	catch (ErrorException $e)
    	{
    		$infos = '';
    	}
    	return $this->render('contact',compact("infos",'left'));
    }

    /* 帮助中心 */
    public function actionHelp()
    {
        $left = About::find()->asArray()->all();

    	try
    	{
	    	$parent_id = Category::findOne(['title'=>'帮助中心','status'=>1])->id;
	    	$infos = Article::find()->where(['category_id'=>$parent_id,'status'=>1]);
	    	$pages = new Pagination(['totalCount' => $infos->count(), 'pageSize' => '6']);
	    	$models = $infos->offset($pages->offset)
	    	->limit($pages->limit)
	    	->all();
    	}
    	catch (ErrorException $e)
    	{
    		$infos = '';
    		$pages = '';
    		$models = '';
    	}
    	return $this->render('help',compact("infos","models","pages",'left'));
    }
    
}
