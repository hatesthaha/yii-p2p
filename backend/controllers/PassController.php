<?php
/**
 * Created by PhpStorm.
 * User: wuwenhan
 * Date: 2015/7/27
 * Time: 8:53
 */
namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class PassController extends Controller{
    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
        ];
    }
    public function actionUpdate()
    {

        $model = User::find()->where([ 'id'=>\App::$app->user->identity->getId()])->One();
        $model->setScenario('admin-update');
        if ($model->load(\App::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}