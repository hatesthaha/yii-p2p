<?php

namespace backend\controllers;

use Yii;
use common\models\base\experience\Gold;
use common\models\base\experience\GoldSearch;
use common\models\base\experience\Rule;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GoldController implements the CRUD actions for Gold model.
 */
class GoldController extends Controller
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
     * Lists all Gold models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoldSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Gold model.
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
     * Creates a new Gold model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Gold();

        if ($model->load(Yii::$app->request->post()) ) {
            $rule = Rule::find()->andWhere(['id'=>6,'status' => Rule::STATUS_ACTIVE])->one();
            if($rule){
                $model->money = \App::$app->request->post()['Gold']['money'];
                $model->uid = \App::$app->request->post()['Gold']['uid'];
                $model->rid = 6;
                $model->end_at = $rule->time *3600*24 + strtotime(date('Y-m-d H:i:s',time()));
                $model->title = '系统赠送体验金';
                $model->save();
                return $this->redirect(['index']);
            }else{
                return $this->redirect(['index']);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Gold model.
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
     * Deletes an existing Gold model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        Gold::updateAll(['status' => Gold::STATUS_DELETED],['id' => $id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Gold model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Gold the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gold::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
