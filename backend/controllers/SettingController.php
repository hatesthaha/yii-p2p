<?php

namespace backend\controllers;

use common\models\fund\Thirdproduct;
use common\models\setting\Setting;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use common\models\base\fund\Product;
use common\models\fund\FundProductThirdproduct;
use yii\web\UploadedFile;
class SettingController extends Controller
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
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        if(\App::$app->request->isPost)
        {
            $siteRoot = str_replace('\\', '/', realpath(dirname(dirname(dirname(__FILE__))) . '/')) . "/www/web/upload/";
            if (!empty($_FILES)) {
                if( $_FILES['Setting']['tmp_name']['img']){
                $tempPath = $_FILES['Setting']['tmp_name']['img'];
                $filesName = uniqid() . '.' . pathinfo($_FILES['Setting']['name']['img'], PATHINFO_EXTENSION);
                $uploadPath = $siteRoot . $filesName;

                move_uploaded_file($tempPath, $uploadPath);
                Setting::updateAll(['value' => $filesName], ['code' => 'img']);
                }
            }
            $setting = \App::$app->request->post('Setting');
            foreach($setting as $key => $value) {
                Setting::updateAll(['value' => $value], ['code' => $key]);
            }
        }

        $settingParent = Setting::find()->where(['parent_id' => 0])->orderBy(['sort_order' => SORT_ASC])->all();
        return $this->render('index', [
            'settingParent' => $settingParent,
        ]);
    }

}
