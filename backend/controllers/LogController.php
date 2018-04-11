<?php

namespace backend\controllers;

use Yii;
use common\models\base\asset\Log;
use common\models\base\asset\LogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use framework\helpers\Utils;
use yii\filters\AccessControl;
/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller
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
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Log model.
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
     * Creates a new Log model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Log();

        if ($model->load(\App::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    private function logList($startTime = 0, $endTime = 0){
        $endTime = $endTime == 0 ? time() : $endTime;
        $logs = Log::find()
            ->select('asset_log.id,asset_log.member_id,asset_log.step,asset_log.status,asset_log.bankcard,asset_log.create_at')
            ->joinWith('member')
            ->andWhere(['between', 'asset_log.create_at',$startTime,$endTime ])
            ->asArray()
            ->all();
        foreach($logs as &$log){
            $log['id'] = $log['id'];

            $log['username'] = $log['member']['username'];
            unset($log['member_id']);
            unset($log['member']);

            $log['step'] = $log['step'];
            $log['bankcard'] = $log['bankcard'];
            $log['create_at'] = date('Y-m-d', $log['create_at']);
            switch ($log['status']) {
                case Log::STATUS_WITHDRAW_SUC:
                    $log['status'] = '投资成功';
                    break;
                case Log::STATUS_WITHDRAW_ERR:
                    $log['status'] = '投资失败';
                    break;
                case Log::STATUS_INVEST_SUC:
                    $log['status'] = '提现成功';
                    break;
                case Log::STATUS_INVEST_ERR:
                    $log['status'] = '提现失败';
                    break;
                case Log::STATUS_REDEM_SUC:
                    $log['status'] = '赎回成功';
                    break;
                case Log::STATUS_REDEM_ERR:
                    $log['status'] = '赎回失败';
                    break;
                case Log::STATUS_RECHAR_SUC:
                    $log['status'] = '充值成功';
                    break;
                case Log::STATUS_RECHAR_ERR:
                    $log['status'] = '充值失败';
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $logs;

    }

    public function actionExport(){
        $time = \App::$app->request->post()['time'];
        $arr[0] = 0;
        $arr[1] = 0;
        if($time) {
            $arr = explode('至', \App::$app->request->post()['time']);
            $arr[0] = strtotime($arr[0].' 00:00:00');
            $arr[1] = strtotime($arr[1].' 23:59:59');
        }
        $logs = $this->logList($arr[0],$arr[1]);

        Utils::exportExcel($logs,
            [
                'ID','金额', '状态','银行卡', '创建时间','用户名'
            ], '资金记录导出' . date('Y.m.d')
        );
    }

    /**
     * Updates an existing Log model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\App::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Log model.
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
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
