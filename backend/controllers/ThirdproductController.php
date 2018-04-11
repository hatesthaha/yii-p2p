<?php

namespace backend\controllers;

use Yii;
use common\models\fund\Thirdproduct;
use common\models\fund\ThirdproductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use backend\models;
use common\models\base\site\Operating;
/**
 * ThirdproductController implements the CRUD actions for Thirdproduct model.
 */
class ThirdproductController extends Controller
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
     * Lists all Thirdproduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ThirdproductSearch();
        $dataProvider = $searchModel->addsearch(\App::$app->request->queryParams);
        //Operating::addlog(\App::$app->user->identity->username,'查看第三方债权');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Thirdproduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $data = $this->findModel($id);
        $data['rate'] = ($data['rate']*100).'%';
        $data['start_at'] = date("Y-m-d H:i", $data['start_at']);
        $data['end_at'] = date("Y-m-d H:i", $data['end_at']);
        $data['create_at'] = date("Y-m-d H:i", $data['create_at']);
        $data['update_at'] = date("Y-m-d H:i", $data['update_at']);
        return $this->render('view', [
            'model' => $data,
        ]);
    }

    /**
     * Creates a new Thirdproduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Thirdproduct();

        if ($model->load(\App::$app->request->post())) {

            $model->contract = UploadedFile::getInstance($model, 'contract');
            $model->realname = $model->contract;
            if($model->contract){
                $contractName = mt_rand(1100,9900) .time() .'.'. $model->contract->extension;
                $model->contract->saveAs('upload/'.$contractName);
                $model->contract = $contractName;
            }

            $model->intentcontract = UploadedFile::getInstance($model, 'intentcontract');
            $model->intentrealname = $model->intentcontract;
            if($model->intentcontract){
                $intentcontractName = mt_rand(1100,9900) .time() .'.'. $model->intentcontract->extension;
                $model->intentcontract->saveAs('upload/'.$intentcontractName);
                $model->intentcontract = $intentcontractName;
            }

            $model->create_user_id = \App::$app->user->identity->getId();
            $model->start_at = strtotime(\App::$app->request->post()["Thirdproduct"]['start_at']);
            $model->end_at = strtotime(\App::$app->request->post()["Thirdproduct"]['end_at']);
            $model->intent = \App::$app->request->post()["intent"];
            $model->title = \App::$app->request->post()["Thirdproduct"]['title'];
            $model->intro = \App::$app->request->post()["Thirdproduct"]['intro'];
            $model->source = \App::$app->request->post()["Thirdproduct"]['source'];
            $model->creditor = \App::$app->request->post()["Thirdproduct"]['creditor'];
            $model->maxcreditor = \App::$app->request->post()["Thirdproduct"]['maxcreditor'];
            $model->rate = \App::$app->request->post()["Thirdproduct"]['rate']*0.01;
            $model->remarks = \App::$app->request->post()["Thirdproduct"]['remarks'];
            $model->amount = \App::$app->request->post()["Thirdproduct"]['amount'];
            $model->ocmoney = \App::$app->request->post()["Thirdproduct"]['amount'];
            $model->create_user_id = \App::$app->user->identity->getId();
            $model->save(false);
            Operating::addlog(\App::$app->user->identity->username,'创建第三方债权');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Thirdproduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateyi()
    {
        $model = new Thirdproduct();

        if ($model->load(\App::$app->request->post())) {

            $model->contract = UploadedFile::getInstance($model, 'contract');

            if($model->contract){
                $model->realname = $model->contract;
                $contractName = mt_rand(1100,9900) .time() .'.'. $model->contract->extension;
                $model->contract->saveAs('upload/'.$contractName);
                $model->contract = $contractName;
            }

            $model->intentcontract = UploadedFile::getInstance($model, 'intentcontract');

            if($model->intentcontract){
                $model->intentrealname = $model->intentcontract;
                $intentcontractName = mt_rand(1100,9900) .time() .'.'. $model->intentcontract->extension;
                $model->intentcontract->saveAs('upload/'.$intentcontractName);
                $model->intentcontract = $intentcontractName;
            }

            $model->create_user_id = \App::$app->user->identity->getId();
            $model->start_at = strtotime(\App::$app->request->post()["Thirdproduct"]['start_at']);
            $model->end_at = strtotime(\App::$app->request->post()["Thirdproduct"]['end_at']);

            $model->intent = \App::$app->request->post()["intent"];
            $model->title = \App::$app->request->post()["Thirdproduct"]['title'];
            $model->intro = \App::$app->request->post()["Thirdproduct"]['intro'];
            $model->source = \App::$app->request->post()["Thirdproduct"]['source'];
            $model->creditor = \App::$app->request->post()["Thirdproduct"]['creditor'];
            $model->maxcreditor = \App::$app->request->post()["Thirdproduct"]['maxcreditor'];
            $model->rate = \App::$app->request->post()["Thirdproduct"]['rate']*0.01;
            $model->remarks = \App::$app->request->post()["Thirdproduct"]['remarks'];
            $model->amount = \App::$app->request->post()["Thirdproduct"]['amount'];
            $model->ocmoney = \App::$app->request->post()["Thirdproduct"]['amount'];
            $model->create_user_id = \App::$app->user->identity->getId();

            $model->save();
            Operating::addlog(\App::$app->user->identity->username,'创建第三方债权');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('createyi', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing Thirdproduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\App::$app->request->post())) {
            $model->contract = UploadedFile::getInstance($model, 'contract');



            if($model->contract){
                $model->realname = $model->contract;
                $contractName = mt_rand(1100,9900) .time() .'.'. $model->contract->extension;
                $model->contract->saveAs('upload/'.$contractName);
                $model->contract = $contractName;
            }

            if(!$model->contract){
                $new = $this->findModel($id);
                $model->contract = $new->contract;
                $model->realname = $new->realname;
            }
            $model->intentcontract = UploadedFile::getInstance($model, 'intentcontract');

            if($model->intentcontract){
                $model->intentrealname = $model->intentcontract;
                $intentcontractName = mt_rand(1100,9900) .time() .'.'. $model->intentcontract->extension;
                $model->intentcontract->saveAs('upload/'.$intentcontractName);
                $model->intentcontract = $intentcontractName;
            }
            if(!$model->intentcontract){
                $model->intentcontract = $new->intentcontract;
                $model->intentrealname = $new->intentrealname;
            }
           // exit;
            $model->start_at = strtotime(\App::$app->request->post()["Thirdproduct"]['start_at']);
            $model->end_at = strtotime(\App::$app->request->post()["Thirdproduct"]['end_at']);
            $model->status =  Thirdproduct::STATUS_INACTIVE;
            $model->title = \App::$app->request->post()["Thirdproduct"]['title'];
            $model->intro = \App::$app->request->post()["Thirdproduct"]['intro'];
            $model->source = \App::$app->request->post()["Thirdproduct"]['source'];
            $model->creditor = \App::$app->request->post()["Thirdproduct"]['creditor'];
            $model->maxcreditor = \App::$app->request->post()["Thirdproduct"]['maxcreditor'];
            $model->rate = \App::$app->request->post()["Thirdproduct"]['rate']*0.01;
            $model->remarks = \App::$app->request->post()["Thirdproduct"]['remarks'];
            $model->amount = \App::$app->request->post()["Thirdproduct"]['amount'];
            $model->ocmoney = \App::$app->request->post()["Thirdproduct"]['amount'];

            Operating::addlog(\App::$app->user->identity->username,'更新第三方债权');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model['start_at'] = date("Y-m-d H:i", $model['start_at']);
            $model['end_at'] = date("Y-m-d H:i", $model['end_at']);
            $model['rate'] = $model['rate']*100;
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Thirdproduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        Thirdproduct::updateAll(['status'=>Thirdproduct::STATUS_DELETED],['id' =>$id]);
        Operating::addlog(\App::$app->user->identity->username,'删除第三方债权');
        return $this->redirect(['index']);
    }
    public function actionReject($id){
        $model = $this->findModel($id);

        if ($model->load(\App::$app->request->post()) ) {

            $model->reject = \App::$app->request->post()["Thirdproduct"]['reject'];
            $model->status = Thirdproduct::STATUS_REGECT;
            $model->save();
            return $this->redirect(['check']);
        } else {

            return $this->render('reject', [
                'model' => $model,
            ]);
        }
    }
    public function actionChange($id){

        $model = Thirdproduct::findOne($id);

        Thirdproduct::updateAll(['status'=>Thirdproduct::STATUS_INACTIVE,'intent'=>Thirdproduct::INTENT_CHECK],['id' => $id]);
        return $this->redirect(['index']);

    }
    public function actionCheck(){
        $searchModel = new ThirdproductSearch();
        if(isset(\App::$app->request->post()["selection"]))
        {
        	$checks = \App::$app->request->post()["selection"];
        	foreach ($checks as $id)
        	{
        		$model = ThirdproductSearch::findOne(['id' => $id]);
        		if($model->status == 0)
        		{
                    $model->check_user_id =  \App::$app->user->identity->getId();
	        		$model->status=1;
	        		$model->save();
        		}
        	}

        	return $this->redirect(['check']);
        }
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);

        return $this->render('check', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUncheck(){
        $searchModel = new ThirdproductSearch();
        if(isset(\App::$app->request->post()["selection"]))
        {
            $checks = \App::$app->request->post()["selection"];
            foreach ($checks as $id)
            {
                $model = ThirdproductSearch::findOne(['id' => $id]);
                if($model->status == 1)
                {
                    $model->status=0;
                    $model->save();
                }
            }

            return $this->redirect(['check']);
        }
        $dataProvider = $searchModel->unsearch(\App::$app->request->queryParams);

        return $this->render('uncheck', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Finds the Thirdproduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Thirdproduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Thirdproduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
