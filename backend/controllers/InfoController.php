<?php

namespace backend\controllers;

use common\models\base\ucenter\Catmiddle;
use common\models\UcenterMember;
use Yii;
use common\models\base\asset\Info;
use common\models\base\asset\InfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\actions\sinapay;
use common\models\sinapay\SinaMember;
use common\models\sinapay\SinaBank;
use frontend\actions\sina;
use yii\base\ErrorException;
/**
 * InfoController implements the CRUD actions for Info model.
 */
class InfoController extends Controller
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
     * Lists all Info models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InfoSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOrderBalance(){
        $searchModel = new InfoSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Info model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $sina = new sina();
        $smember = SinaMember::find()->andWhere(['uid'=>$model->member_id])->andWhere(['status'=>SinaMember::STATUS_BINGING])->one();
        if($smember){
            //$sina = new sina();

            $model->sinamoney = $sina->query_balance($smember->identity_id);

            //var_dump($model->sinamoney);
            //判定是员工才更新余额
            $user = Catmiddle::find()->where(['uid' => $model->member_id])->one();
            if($user){
                if($user->cid == '1' || $user->cid == '2' || $user->cid == '3' ){
                    Info::updateAll(['balance'=>$model->sinamoney['balance']],['member_id' => $id]);
                    $model->balance = $model->sinamoney['balance'];
                }
            }else{
                $model->balance = Info::findOne($id)->balance;
            }


        }else{
            \Yii::$app->getSession()->setFlash('warning', '该用户没用绑定新浪账户');
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }
    public function actionCollect($id){
        $model = $this->findModel($id);
        $smember = SinaMember::find()->andWhere(['uid'=>$model->member_id, 'status'=>SinaMember::STATUS_BINGING])->one();
        if($smember && $smember->status==1){
            //$sina = new sina();
           // $model->sinamoney = $sina->query_balance('1440052548HQW131102199007042019');
        }else{
            \Yii::$app->getSession()->setFlash('warning', '该用户没用绑定新浪账户');
            return $this->redirect(['index']);
        }

        if (\App::$app->request->post() ) {

            //事物回滚
            $transaction = \App::$app->db->beginTransaction();
            try {
                $model->balance += \App::$app->request->post()['Info']['fabalance'];
                if (!$model->save()) {
                    throw new ErrorException('用户金钱存储失败', 1);
                }
                $pay_uid = array(
                    $model->member_id => \App::$app->request->post()['Info']['fabalance'],
                );
                $return = sinapay::collectSite(\App::$app->request->post()['Info']['fabalance'],$pay_uid);
                if($return['errorNum'] == 1){
                    throw new ErrorException($return['errorMsg'], 1);
                }
                $transaction->commit();
            }catch (ErrorException $e){
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('warning', $e->getMessage());
                $newmodel = $this->findModel($id);
                return $this->render('collect', [
                    'model' => $newmodel,
                ]);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('collect', [
                'model' => $model,
            ]);
        }
    }
    public function actionUncollect($id){
        $model = $this->findModel($id);
        $smember = SinaMember::find()->andWhere(['uid'=>$model->member_id])->one();
        if($smember && $smember->status==1){
            //$sina = new sina();
            // $model->sinamoney = $sina->query_balance('1440052548HQW131102199007042019');
        }else{
            \Yii::$app->getSession()->setFlash('warning', '该用户没用绑定新浪账户');
            return $this->redirect(['index']);
        }

        if (\App::$app->request->post() ) {

            //事物回滚
            $transaction = \App::$app->db->beginTransaction();
            try {
                $model->balance -= \App::$app->request->post()['Info']['fabalance'];
                if($model->balance<1){
                    throw new ErrorException('提取金额大于用户余额', 1);
                }
                if (!$model->save()) {
                    throw new ErrorException('用户金钱存储数据失败', 1);
                }

                $return = sinapay::collectUser($model->member_id,\App::$app->request->post()['Info']['fabalance']);
                if($return['errorNum'] == 1){
                    throw new ErrorException($return['errorMsg'], 1);
                }
                $transaction->commit();
            }catch (ErrorException $e){
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('warning', $e->getMessage());
                $newmodel = $this->findModel($id);
                return $this->render('uncollect', [
                    'model' => $newmodel,
                ]);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('uncollect', [
                'model' => $model,
            ]);
        }
    }
    public function actionLock($id){
        $model = $this->findModel($id);
        $sina = new sina();
        $smember = SinaMember::find()->andWhere(['uid'=>$model->member_id])->one();
        if($smember && $smember->status==1){
            //$sina = new sina();
            $model->sinamoney = $sina->query_balance($smember->identity_id);
        }else{
            \Yii::$app->getSession()->setFlash('warning', '该用户没用绑定新浪账户');
        }
        if (\App::$app->request->post() ) {
            if(\App::$app->request->post()['Info']['balance']>$model->balance){
                return $this->render('lock', [
                    'model' => $model,
                ]);
            }else{
                sinapay::balanceFreeze($id,\App::$app->request->post()['Info']['balance'],'网站冻结');
                $model->balance -= \App::$app->request->post()['Info']['balance'];
                $model->freeze += \App::$app->request->post()['Info']['balance'];
                $model->save();
                return $this->redirect(['view', 'id' => $model->member_id]);
            }

        } else {
            return $this->render('lock', [
                'model' => $model,
            ]);
        }
    }
    public function actionUnbundling($id){
        $model = $this->findModel($id);
        if($model->balance){
            \Yii::$app->getSession()->setFlash('warning', '账户还有余额，请处理完余额再解绑银行卡');
            return $this->render('view', [
                'model' => $model,
            ]);

        }

        $return =  sinapay::unbinding_bank_card($model->member_id);

        if($return['errorNum'] == 0){
            \Yii::$app->getSession()->setFlash('warning', '解绑成功');
            UcenterMember::updateAll(['status'=>UcenterMember::STATUS_REAL],['id' => $model->member_id]);
//            SinaBank::updateAll(['status'=>SinaBank::STATUS_DELETED],['id' => $model->member_id,'status'=>SinaBank::STATUS_BINGING]);
//            Info::updateAll(['bank_card'=>''],['id' => $model->member_id]);
        }else{
            \Yii::$app->getSession()->setFlash('warning', $return['errorMsg']);
        }
        return $this->render('view', [
            'model' => $model,
        ]);



    }
    public function actionUnlock($id){
        $model = $this->findModel($id);
        $sina = new sina();
        $smember = SinaMember::find()->andWhere(['uid'=>$model->member_id])->one();
        if($smember && $smember->status==1){
            //$sina = new sina();
            $model->sinamoney = $sina->query_balance($smember->identity_id);
        }else{
            \Yii::$app->getSession()->setFlash('warning', '该用户没用绑定新浪账户');
        }

        if (\App::$app->request->post() ) {
            if(\App::$app->request->post()['Info']['freeze']>$model->freeze){
                return $this->render('unlock', [
                    'model' => $model,
                ]);
            }else{
                $model->freeze -= \App::$app->request->post()['Info']['freeze'];
                $model->balance += \App::$app->request->post()['Info']['freeze'];
                $model->save();
                return $this->redirect(['view', 'id' => $model->member_id]);
            }

        } else {
            return $this->render('unlock', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Creates a new Info model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Info();

        if ($model->load(\App::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->member_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Info model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\App::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->member_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Info model.
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
     * Finds the Info model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Info the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Info::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
