<?php

namespace backend\controllers;

use Yii;
use backend\models\Article;
use backend\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use xj\ueditor\Ueditor;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
    public function actions() {
        return [
            'upload' => [
                'class' => \xj\ueditor\actions\Upload::className(),
                'uploadBasePath' => '@webroot/upload', //file system path
                'uploadBaseUrl' => '@web/upload', //web path
                'csrf' => true, //csrf校验
                'configPatch' => [
                    'imageMaxSize' => 500 * 1024, //图片
                    'scrawlMaxSize' => 500 * 1024, //涂鸦
                    'catcherMaxSize' => 500 * 1024, //远程
                    'videoMaxSize' => 1024 * 1024, //视频
                    'fileMaxSize' => 1024 * 1024, //文件
                    'imageManagerListPath' => '/', //图片列表
                    'fileManagerListPath' => '/', //文件列表
                ],
                // OR Closure
                'pathFormat' => [
                    'imagePathFormat' => 'image/{yyyy}{mm}{dd}/{time}{rand:6}',
                    'scrawlPathFormat' => 'image/{yyyy}{mm}{dd}/{time}{rand:6}',
                    'snapscreenPathFormat' => 'image/{yyyy}{mm}{dd}/{time}{rand:6}',
                    'snapscreenPathFormat' => 'image/{yyyy}{mm}{dd}/{time}{rand:6}',
                    'catcherPathFormat' => 'image/{yyyy}{mm}{dd}/{time}{rand:6}',
                    'videoPathFormat' => 'video/{yyyy}{mm}{dd}/{time}{rand:6}',
                    'filePathFormat' => 'file/{yyyy}{mm}{dd}/{time}{rand:6}',
                ],
                // For Closure
                'pathFormat' => [
                    'imagePathFormat' => [$this, 'format'],
                    'scrawlPathFormat' => [$this, 'format'],
                    'snapscreenPathFormat' => [$this, 'format'],
                    'snapscreenPathFormat' => [$this, 'format'],
                    'catcherPathFormat' => [$this, 'format'],
                    'videoPathFormat' => [$this, 'format'],
                    'filePathFormat' => [$this, 'format'],
                ],
                'beforeUpload' => function($action) {
                },
                'afterUpload' => function($action) {

                },
            ],
        ];
    }
    // for Closure Format
    public function format(\xj\ueditor\actions\Uploader $action) {
        $fileext = $action->fileType;
        $filehash = sha1(uniqid() . time());
        $p1 = substr($filehash, 0, 2);
        $p2 = substr($filehash, 2, 2);
        return "{$p1}/{$p2}/{$filehash}.{$fileext}";
    }
    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);
        $arrayCatalog = Article::getArrayCategory();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionSend($id){
        Article::updateAll(['status' => Article::STATUS_ACTIVE], ['id' => $id]);
        return $this->redirect(Url::to(['index']));
    }
    public function actionUnsend($id){
        Article::updateAll(['status' => Article::STATUS_INACTIVE], ['id' => $id]);
        return $this->redirect(Url::to(['index']));
    }
    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(\App::$app->request->post()) ) {
            $model->logo = UploadedFile::getInstance($model, 'logo');
            if($model->logo){
                $contractName = mt_rand(1100,9900) .time() .'.'. $model->logo->extension;
                $model->logo->saveAs('upload/'.$contractName);
                $model->logo = $contractName;
            }
            $model->user_id = \App::$app->request->post()['Article']['user_id'];
            $model->category_id = \App::$app->request->post()['Article']['category_id'];
            $model->title = \App::$app->request->post()['Article']['title'];
            $model->intro = \App::$app->request->post()['Article']['intro'];
            $model->content = \App::$app->request->post()['Article']['content'];
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\App::$app->request->post()) ) {
            $model->logo = UploadedFile::getInstance($model, 'logo');

            if($model->logo){
                $contractName = mt_rand(1100,9900) .time() .'.'. $model->logo->extension;
                $model->logo->saveAs('upload/'.$contractName);
                $model->logo = $contractName;
            }
            if(!$model->logo){
                $new = $this->findModel($id);
                $model->logo = $new->logo;

            }

            $model->user_id = \App::$app->request->post()['Article']['user_id'];
            $model->category_id = \App::$app->request->post()['Article']['category_id'];
            $model->title = \App::$app->request->post()['Article']['title'];
            $model->intro = \App::$app->request->post()['Article']['intro'];
            $model->content = \App::$app->request->post()['Article']['content'];
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        Article::updateAll(['status'=>Article::STATUS_DELETED],['id' =>$id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
