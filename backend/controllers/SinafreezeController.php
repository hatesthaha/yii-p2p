<?php

namespace backend\controllers;

use common\models\base\asset\Info;
use frontend\actions\sinapay;
use Yii;
use common\models\sinapay\SinaFreeze;
use common\models\sinapay\SinaFreezeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SinafreezeController implements the CRUD actions for SinaFreeze model.
 */
class SinafreezeController extends Controller
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
        ];
    }

    /**
     * Lists all SinaFreeze models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SinaFreezeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SinaFreeze model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SinaFreeze model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SinaFreeze();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing SinaFreeze model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUnlock($id)
    {
        $model = $this->findModel($id);

        $return = sinapay::balanceUnfreeze($model->out_freeze_no,$model->freeze_money,'解冻金额');
        if($return['errorNum'] == 0){
            \Yii::$app->getSession()->setFlash('warning', $return['errorMsg']);
            $info = new Info();
            $info->freeze -= $model->freeze_money;
            $info->balance += $model->freeze_money;
            $info->save();
        }else{
            \Yii::$app->getSession()->setFlash('warning', $return['errorMsg']);
        }
        return $this->redirect(['index']);
    }
    /**
     * Updates an existing SinaFreeze model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SinaFreeze model.
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
     * Finds the SinaFreeze model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SinaFreeze the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SinaFreeze::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
