<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\base\activity\RaiseCard;
use common\models\base\activity\RaiseCardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Command;

/**
 * RaiseCardController implements the CRUD actions for RaiseCard model.
 */
class RaisecardController extends Controller
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
     * Lists all RaiseCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RaiseCardSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);
        $arrayStatus = RaiseCard::labels();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RaiseCard model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $query = $this->findModel($id);
        $query->rate = $query->rate;
        $query->validity_start_at = date('Y-m-d',$query->validity_start_at);
        $query->validity_out_at = date('Y-m-d',$query->validity_out_at);
        return $this->render('view', [
            'model' => $query,
        ]);
    }

    /**
     * Creates a new RaiseCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RaiseCard();

        if ($model->load(\App::$app->request->post())) {
            $model->validity_start_at = strtotime(\App::$app->request->post()["RaiseCard"]['validity_start_at']);
            $model->validity_out_at = strtotime(\App::$app->request->post()["RaiseCard"]['validity_out_at']);
            $model->status = \App::$app->request->post()['RaiseCard']['status'];
            $model->rate = \App::$app->request->post()['RaiseCard']['rate'];
            $model->member_id = \App::$app->request->post()['RaiseCard']['member_id'];
            $model->update_at = strtotime("now");
            $model->create_at = strtotime("now");
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RaiseCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\App::$app->request->post()) ) {
            $model->validity_start_at = strtotime(\App::$app->request->post()["RaiseCard"]['validity_start_at']);
            $model->validity_out_at = strtotime(\App::$app->request->post()["RaiseCard"]['validity_out_at']);
            $model->status = \App::$app->request->post()['RaiseCard']['status'];
            $model->rate = \App::$app->request->post()['RaiseCard']['rate'];
            $model->update_at = strtotime("now");
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->validity_start_at = date('Y-m-d',$model->validity_start_at);
            $model->validity_out_at = date('Y-m-d',$model->validity_out_at);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RaiseCard model.
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
     * Finds the RaiseCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RaiseCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RaiseCard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
