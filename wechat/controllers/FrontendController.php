<?php

namespace wechat\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class FrontendController extends Controller
{
    public $layout = 'layout';

	
	
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['signup', 'signin','member','index','login','logout','createmenu','enter','main','detaile','step1','step2','reg','dosignin','forgot','forgotstep1','forgotfinish','bindcard','about','help','contact','safety','gindex','gshare','gsignup','dorecharge','productlist'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function () {
                    \Yii::$app->getSession()->setFlash("errors", ['info' => '']);
                    return $this->redirect(Url::to(['site/signin']));
                }
            ],
        ];
    }
    public function goBack($params = null, $defaultUrl = null)
    {
        if ($params !== null) {
            \Yii::$app->getSession()->setFlash("errors", $params);
        }
        return $this->redirect($defaultUrl);
    }
    /**
     * 动态改变 Response 方式，如果是ajax则使用json返回，请使用此方法进行Html以及JOSN进行返回
     * @param $view
     * @param array $params
     * @return array|string
     */
    public function view($view, $params = [])
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $params;
        } else {
            return $this->render($view, $params);
        }
    }
}