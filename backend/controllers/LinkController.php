<?php

namespace backend\controllers;

use Yii;
use common\models\base\cms\Link;
use common\models\base\cms\LinkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
/**
 * LinkController implements the CRUD actions for Link model.
 */
class LinkController extends Controller
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
     * Lists all Link models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Link model.
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
     * Creates a new Link model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Link();

        if ($model->load(\App::$app->request->post()) ) {
            $model->bannar = UploadedFile::getInstance($model, 'bannar');
            if($model->bannar){
                $contractName = mt_rand(1100,9900) .time() .'.'. $model->bannar->extension;
                $model->bannar->saveAs('upload/'.$contractName);
                $model->bannar = $contractName;
            }
            $model->cat_id = \App::$app->request->post()['Link']['cat_id'];
            $model->intro = \App::$app->request->post()['Link']['intro'];
            $model->status = \App::$app->request->post()['Link']['status'];
            $model->link = \App::$app->request->post()['Link']['link'];

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Link model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\App::$app->request->post())) {

            $model->bannar = UploadedFile::getInstance($model, 'bannar');
            if($model->bannar){
                $contractName = mt_rand(1100,9900) .time() .'.'. $model->bannar->extension;
                $model->bannar->saveAs('upload/'.$contractName);
                $model->bannar = $contractName;
            }
            if(!$model->bannar){
                $new = $this->findModel($id);
                $model->bannar = $new->bannar;

            }

            $model->cat_id = \App::$app->request->post()['Link']['cat_id'];
            $model->intro = \App::$app->request->post()['Link']['intro'];
            $model->status = \App::$app->request->post()['Link']['status'];
            $model->link = \App::$app->request->post()['Link']['link'];

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Link model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        Link::updateAll(['status'=>Link::STATUS_DELETED],['id' =>$id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Link model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Link the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Link::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
