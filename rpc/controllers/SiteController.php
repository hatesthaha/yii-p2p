<?php
/**
 * @author: liushaohua
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 * @time 2015年7月5日 09:15:54
 * @QQ:489122117
 */

namespace rpc\controllers;


use Yii;

use yii\web\Controller;


/**
 * Site controller
 */
class SiteController extends Controller
{
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

    }
    public function actionIndex()
    {
        echo "123";
	 exit;
    }


}
