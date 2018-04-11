<?php

namespace backend\controllers;

use Yii;
use common\models\base\fund\Order;
use common\models\base\fund\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use framework\helpers\Utils;
use yii\filters\AccessControl;
/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $time = date('Y-m-d 至 Y-m-d',time());
        if(\App::$app->request->post()){
            $time = \App::$app->request->post()['time'];
        }
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'time' =>$time,
        ]);
    }

    private function orderList($startTime = 0, $endTime = 0){
        $endTime = $endTime == 0 ? time() : $endTime;
        $orders = Order::find()
            ->select('fund_orders.id,fund_orders.member_id,fund_orders.product_id,fund_orders.money,fund_orders.start_at')
            ->joinWith('member')
            ->joinWith('product')
            ->andWhere(['between', 'fund_orders.start_at',$startTime,$endTime ])
            ->asArray()
            ->all();
        foreach($orders as &$order){
            $order['id'] = $order['id'];

                $order['username'] = $order['member']['username'];
                unset($order['member_id']);
                unset($order['member']);


                $order['productname'] = $order['product']['title'];
                unset($order['product']);
                unset($order['product_id']);

            $order['money'] = $order['money'];
            $order['start_at'] = date('Y-m-d', $order['start_at']);
        }

        return $orders;
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
        $orders = $this->orderList($arr[0],$arr[1]);

        Utils::exportExcel($orders,
            [
                'ID','金额', '创建时间','用户名', '商品名'
            ], '订单记录导出' . date('Y.m.d')
        );
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
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
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
