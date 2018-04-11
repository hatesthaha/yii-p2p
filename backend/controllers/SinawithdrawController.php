<?php

namespace backend\controllers;

use common\models\base\asset\Info;
use frontend\actions\sina;
use frontend\actions\sinapay;
use Yii;
use common\models\sinapay\SinaWithdraw;
use common\models\sinapay\SinaWithdrawSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\sinapay\SinaInvest;

/**
 * SinawithdrawController implements the CRUD actions for SinaWithdraw model.
 */
class SinawithdrawController extends Controller
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
     * Lists all SinaWithdraw models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SinaWithdrawSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SinaWithdraw model.
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
     * Creates a new SinaWithdraw model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SinaWithdraw();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SinaWithdraw model.
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
     * Deletes an existing SinaWithdraw model.
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
     * Finds the SinaWithdraw model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SinaWithdraw the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SinaWithdraw::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 用户退款
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRefund(){
        if (\App::$app->request->post()) {

            $uid =  \App::$app->request->post()['Code']['uid'];
            $orig_outer_trade_no = \App::$app->request->post()['Code']['out_trade_no'];
            $refund_amount = \App::$app->request->post()['Code']['money'];
            $summary = \App::$app->request->post()['Code']['msg'];
            //获取新浪的账户余额
            $sina_balance = sinapay::querySinaBalance($uid);
            if($sina_balance['errorNum'] == '0'){
                $available_balance = $sina_balance['data']['available_balance'];
            }
            //获取网站的账户信息
            $balance = Info::findOne(['member_id' => $uid]);
            $site_balabce = $balance['balance'];
            //调用新浪退款接口
            $sina = new sina();
            $out_trade_no =  date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $refund = $sina->create_hosting_refund($out_trade_no,$orig_outer_trade_no,$refund_amount,$summary);
            if(!$refund){
                throw new NotFoundHttpException('新浪接口错误');
            }
            if($refund['response_code'] == "APPLY_SUCCESS"){
                //提交信息成功
                if($refund['refund_status'] == 'SUCCESS' || $refund['refund_status'] == 'PAY_FINISHED'){
                    //更新账户信息
                    $sina_balance = sinapay::querySinaBalance($uid);
                    if($sina_balance['errorNum'] == '0'){
                        $available_balance_now = $sina_balance['data']['available_balance'];
                    }
                    //获取网站的账户信息
                    $balance = Info::findOne(['member_id' => $uid]);
                    $site_balabce_now = $balance['balance'];
                    //返回数据
                    $data = array(
                        'available_balance' => $available_balance,
                        'site_balabce' => $site_balabce,
                        'available_balance_now' => $available_balance_now,
                        'site_balabce_now' => $site_balabce_now
                    );
                    return $this->render('import',compact("data"));

                }else{
                    throw new NotFoundHttpException($refund['response_message']);
                }
            }else{
                throw new NotFoundHttpException($refund['response_message']);

            }
        } else {
            $data = array(
                'available_balance' => '-1',
                'site_balabce' => '-2',
                'available_balance_now' => '-3',
                'site_balabce_now' => '-4'
            );
            return $this->render('import',compact("data"));
        }
    }
}
