<?php
namespace wechat\controllers;

use Yii;
use yii\base\Event;
use yii\helpers\Url;

/**
 * Site controller
 */
class XieyiController extends FrontendController
{
    public function actionIndex()
    {
        return $this->view('index');
    }
}




