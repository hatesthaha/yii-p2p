<?php

namespace backend\controllers;

use Yii;
use common\models\base\activity\Code;
use common\models\base\activity\Card;
use common\models\base\activity\CodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * CodeController implements the CRUD actions for Code model.
 */
class CodeController extends Controller
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
     * Lists all Code models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CodeSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Code model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model['rate'] = $model['rate']*100;
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Code model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Code();

        if ($model->load(\App::$app->request->post()) ) {
            $id = \App::$app->request->post()['Code']['coupon_id'];
            $card = Card::find($id)->asArray()->one();

            $model->coupon_id = \App::$app->request->post()['Code']['coupon_id'];
            $model->validity_time = $card['validity_time'];
            $model->use_end_time = $card['use_out_at'];
            $model->use_at = $card['use_start_at'];
            $model->name = \App::$app->request->post()['Code']['name'];
            $model->rate = $card['rate'];
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Code model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionImport()
    {
        $model = new Code();

        if ($model->load(\App::$app->request->post())) {

            $id = \App::$app->request->post()['Code']['coupon_id'];
            $card = Card::find($id)->asArray()->one();

            $num = \App::$app->request->post()['Code']['num'];
            for ($i = 0; $i <= $num; $i++)
            {
                $_model = new Code();
                $_model->name = Code::createcode(18);
                $_model->coupon_id = \App::$app->request->post()['Code']['coupon_id'];
                $_model->rate = $card['rate'];
                $_model->validity_time = $card['validity_time'];
                $_model->use_end_time = $card['use_out_at'];
                $_model->use_at = $card['use_start_at'];
                $_model->display = Code::DISPLAY_USE;
                $_model->save();
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('import', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Code model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\App::$app->request->post()) ) {
            $id = \App::$app->request->post()['Code']['coupon_id'];
            $card = Card::find($id)->asArray()->one();

            $model->coupon_id = \App::$app->request->post()['Code']['coupon_id'];
            $model->validity_time = $card['validity_time'];
            $model->use_end_time = $card['use_out_at'];
            $model->use_at = $card['use_start_at'];
            $model->name = \App::$app->request->post()['Code']['name'];
            $model->rate = $card['rate'];
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->validity_time = date('Y-m-d',$model->validity_time);
            $model->use_end_time = date('Y-m-d',$model->use_end_time);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Code model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Code::updateAll(['status'=>Code::STATUS_DELETED],['id' =>$id]);
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Code model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Code the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Code::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
