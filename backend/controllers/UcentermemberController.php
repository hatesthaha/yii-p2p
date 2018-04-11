<?php

namespace backend\controllers;

use common\models\User;
use frontend\actions\sinapay;
use Yii;
use common\models\UcenterMember;
use common\models\UcenterMemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use framework\helpers\Utils;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use common\models\sinapay\SinaMember;
use common\models\base\ucenter\Catmiddle;
use common\models\base\ucenter\Cat;
use common\models\sinapay\SinaBank;
use frontend\actions\sina;
/**
 * UcentermemberController implements the CRUD actions for UcenterMember model.
 */
class UcentermemberController extends Controller
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
     * Lists all UcenterMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UcenterMemberSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    private function memberList($startTime = 0, $endTime = 0){
        $endTime = $endTime == 0 ? time() : $endTime;
        $users = UcenterMember::find()
            ->select('id,username,phone,email,idcard,real_name,status,created_at,login_ip')
            ->andWhere(['between', 'created_at',$startTime,$endTime ])
            ->asArray()
            ->all();
        foreach($users as &$user){

            $user['created_at'] = date('Y-m-d', $user['created_at']);
            switch ($user['status']) {
                case UcenterMember::STATUS_ACTIVE:
                    $user['status'] = '注册';
                    break;
                case UcenterMember::STATUS_REAL:
                    $user['status'] = '实名认证';
                    break;
                case UcenterMember::STATUS_BIND:
                    $user['status'] = '绑定银行卡';
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $users;
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
        $users = $this->memberList($arr[0],$arr[1]);

        Utils::exportExcel($users,
            [
                'ID','用户名', '电话','邮箱', '身份证','真实姓名','状态','注册时间','登陆IP'
            ], '用户信息' . date('Y.m.d')
        );
    }
    /**
     * Displays a single UcenterMember model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $sinabank = SinaBank::find()->andWhere(['uid'=>$id])->andWhere(['status'=>SinaBank::STATUS_BINGING])->asArray()->one();
            $sina = new sina();
            if($sinabank['identity_id']){
                $sinabank['sinamoney'] = $sina->query_balance($sinabank['identity_id'])['available_balance'];
            }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'sinabank' => $sinabank,
        ]);
    }

    /**
     * Creates a new UcenterMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new UcenterMember();

        if ($model->load(\App::$app->request->post()) && $model->save()) {


            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UcenterMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $siteRoot = str_replace('\\', '/', realpath(dirname(dirname(dirname(__FILE__))) . '/')) . "/www/web/upload/";
        if ($model->load(\App::$app->request->post()) && $model->save()) {
            $model->person_face = UploadedFile::getInstance($model, 'person_face');
            if ($model->person_face)
            {
                $contractName = mt_rand(1100,9900) .time() .'.'. $model->person_face->extension;
                $model->person_face->saveAs($siteRoot.$contractName);
                $model->person_face = $contractName;
                UcenterMember::updateAll(['person_face' =>$model->person_face], ['id' => $id]);
            }
            if(\App::$app->request->post()['UcenterMember']['lock'] == 0){
                UcenterMember::updateAll(['error_num' =>0 ], ['id' => $id]);
            };
            $cattype = \App::$app->request->post()['UcenterMember']['type'];
            if($cattype){
                Catmiddle::deleteAll('uid = :uid ', [':uid' => $id]);
                foreach($cattype as $k=>$v){
                    $catmiddle = new Catmiddle;
                    $catmiddle->uid = $model->id;
                    $catmiddle->cid = $v;
                    $catmiddle->save();
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UcenterMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       // $this->findModel($id)->delete();
        UcenterMember::updateAll(['lock'=>UcenterMember::TYPE_DELETE],['id' =>$id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the UcenterMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UcenterMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UcenterMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
