<?php

namespace backend\controllers;

use Yii;
use common\models\base\activity\Card;
use common\models\base\activity\CardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use framework\helpers\Utils;
use yii\filters\AccessControl;
/**
 * CardController implements the CRUD actions for Card model.
 */
class CardController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
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

    /**
     * Lists all Card models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CardSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Card model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model['rate'] = $model['rate']*100;
        return $this->render('view', [
            'model' =>$model ,
        ]);
    }

    /**
     * Creates a new Card model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Card();

        if ($model->load(\App::$app->request->post()) ) {
            $model->rate = \App::$app->request->post()['Card']['rate']*0.01;
            $model->use_start_at = strtotime(\App::$app->request->post()['Card']['use_start_at']);
            $model->use_out_at = strtotime(\App::$app->request->post()['Card']['use_out_at']);


            $model->validity_time = Utils::count_days($model->use_start_at,$model->use_out_at);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Card model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);



        if ($model->load(\App::$app->request->post())) {

            $model->rate = \App::$app->request->post()['Card']['rate']*0.01;
            $model->use_start_at = strtotime(\App::$app->request->post()['Card']['use_start_at']);
            $model->use_out_at = strtotime(\App::$app->request->post()['Card']['use_out_at']);
            $model->validity_time = Utils::count_days($model->use_start_at,$model->use_out_at);

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model['rate'] = $model['rate']*100;
            $model['use_start_at'] = date("Y-m-d H:i", $model['use_start_at']);
            $model['use_out_at'] = date("Y-m-d H:i", $model['use_out_at']);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Card model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Card model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Card the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Card::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
