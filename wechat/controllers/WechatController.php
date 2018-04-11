<?php
namespace wechat\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\models\_LoginForm;
use common\models\UcenterMember;
use framework\helpers\Utils;
use frontend\actions\app\member;

class WechatController extends FrontendController
{
    public function actionLogin()
    {
        Utils::ensureOpenId();
        $openId = Yii::$app->request->get('open_id');
        if (!Yii::$app->user->getIsGuest() && ($model = UcenterMember::findOne(['openid' => $openId])) !== null) {
            return $this->redirect(Url::to(['site/member']));
        }
        if($_POST) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $openid = $_POST['open_id'];
            $model = UcenterMember::findOne(['username' => $username]);
            $result = member::login($username,$password,4);
            if($result['errorNum'] ==0){
                if (Yii::$app->user->login($model)) {
                    $model->openid = $openid;
                    $model->save();
                    return $this->redirect(Url::to(['site/member']));
                }
            }else{
                return $this->goBack([
                    'info' => $result['errorMsg']
                ], Url::to(['login']));
            }
        }
        return $this->view('login');
    }
    public function actionDologin()
    {

    }
    public function actionLogout()
    {
        Utils::ensureOpenId();
        $request = Yii::$app->request;

        if ($request->isPost) {

            if (
                ($model = UcenterMember::findOne(['openid' => $request->post('open_id')])) !== null
            ) {

                /** @var MemberOther $model */
                $model->openid = '';
                $model->save();
            }
            Yii::$app->user->logout();
            return $this->goBack([
                'info' => "解绑成功"
            ], Url::to(['site/signin']));
        }
        return $this->view('logout');
    }
    public function actionCreatemenu()
    {
        $url = Yii::$app->urlManager;
        $wechat = Yii::$app->wechat;

        $aa = Yii::$app->wechat->createMenu([
            [
                'name' => '投必火',
                'sub_button' =>
                    [
                        [
                            'type' => 'view',
                            'name' => '我要投资',
                            'url' =>  $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['site/main']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '我的收益',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['money/index']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '我要注册',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['site/signup']))
                        ],
                    ]
            ],
            [
                'type' => 'view',
                'name' => '我要福利',
                'sub_button' =>
                    [
                        [
                            'type' => 'view',
                            'name' => '关于体验金',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['gold/gindex']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '我的账户',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['site/member']))
                        ],

                    ]
            ],
            [
                'name' => '更多帮助',
                'sub_button' =>
                    [
                        [
                            'type' => 'view',
                            'name' => '帮助中心',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['site/help']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '理财王介绍',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['site/about']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '投资安全',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['site/safety']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '开启免登陆',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['wechat/login']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '退出理财王',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['site/logout']))
                        ],
                    ]
            ]
        ]);
        var_dump($aa);
    }
}