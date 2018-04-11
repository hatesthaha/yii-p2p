<?php

namespace www\controllers;

use common\models\sinapay\SinaDeposit;
use common\models\UcenterMember;
use frontend\actions\AloneMethod;
use frontend\actions\App;
use frontend\actions\App\Invest;
use frontend\actions\app\member;
use frontend\actions\app\Port;
use frontend\actions\Bcrdf;
use frontend\actions\sinapay;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\Controller;

class ApiController extends Controller
{

    public function behaviors()
    {
        return [
            'csrf' => [
                'class' => BCrdf::className(),
                'controller' => $this,
                'actions' => [
                    'invest',//投资
                    'userredeem',//赎回,
                    'withdraw',//提现
                    'xproduct',//获取项目详细信息
                    'grabcoupons',//暗码抢加息劵
                    'useraise',//使用加息劵
                    'log',//用户交易记录
                    'kmoney',//获取项目的可投金额
                    'seetotal',//查看网站总的投资数据
                    'phoneregister',//注册手机发送验证码
                    'register',//用户注册
                    'login',//用户登陆
                    'logout',//用户注销
                    'authentication',// 用户实名认证接口
                    'isauthentic',//用户是否实名
                    'returnurl', //支付异步返回
                    'changepassword', //用户修改密码
                    'phonerep', //用户找回密码
                    'resetpassword', //用户重置密码
                    'productlist',//获取项目列表
                    'phonecha', //修改密码，验证手机号
                    'bindingbankcard', //绑定银行卡
                    'bankconfim', //绑定银行卡短信确认
                    'recharge', //用户进行充值
                    'rechargeconfirm', //用户充值确认
                    'usercollect',//用户的昨日收益，再投金额，再投收益，账户余额
                    'profitlog',//用户收益记录
                    'rechargelog',//用户充值记录
                    'investlog',//用户投资记录
                    'withdrawalslog',//用户提现记录
                    'redeemlog',//用户赎回记录
                    'getbankcard', //获取用户银行卡信息
                    'mcentor',//消息中心
                    'feedback',//反馈意见
                    'getuserinfo', //获取用户的详细信息
                    'gettodaysignin', //获取网站今日签到
                    'getyesterdaysignin',//获取网站昨天签到情况
                    'getusersignin', //获取用户签到情况
                    'getyesterdayuser',//获取用户昨日签到收益
                    'signin', //用户签到
                    'issigntoday', //判定用户是否签到
                    'ignoreall', //忽略全部消息
                    'ismsg',  // 判定用户是否有消息
                    'isreading', //判断用户是否读取了消息
                    'readingloog',//把用户阅读加入记录
                    'isexitmsg', // 判定用户是否有新消息
                    'usermsgcentor', //用户消息中心
                    'getsignin', // 获取网站签到情况
                    'getexpgoldlog',//获取体验金记录列表
                    'getlunbo',// app轮播图
                    'getradlist', //获取用户红包列表
                    'getuserredpacket', //获取用户红包金额
                    'checktodaydeposit', //用户充值获取限定
                    'getrecommendrelation', //用户推荐关系列表
                    'rechargeresult', //充值结果查询
                    'getsinglearticle',//获取单条文章内容
                    'getcategoryarticles', //通过名称获得分类下的文章列表
                    'getstart' //获取启动页
                ]
            ]
        ];
    }
////
// 支付信息返回接口
    public function actionReturnurl()
    {
//        $ll = new lianlianClass();
//        $test = $ll->urlReturn();
//        var_dump($test);

        $sina = new sinapay();
        $test = 'success';
        try{
            $test = $sina->notify();

        }catch (Exception $ex){
            Yii::error($ex, 'app');
        }
        return $test;
    }



/////

    /**
     * Auther:langxi
     *$uid, $pid, $money
     * app投资接口
     */
    public function actionInvest()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('pid', $data) && array_key_exists('money', $data)) {
                    $uid = $data['uid'];
                    $pid = $data['pid'];
                    $money = $data['money'];
                    $result = Invest::Invest($uid, $pid, $money);
                    Invest::goldtwo($uid, $money);//体验金
//                    if($result['errorNum'] == '0'){
//                        //投资成功
//                        $user = UcenterMember::findOne(['id'=>$uid]);
//                        $phone = $user->phone;
//                        Port::ValidatePhone2($phone,'2',$money);
//                    }
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }

        } catch (ErrorException $e) {
            Yii::error("app投资接口---异常 方法：actionInvest 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * app赎回接口
     */
    public function actionUserredeem()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('amount', $data)) {
                    $uid = $data['uid'];
                    $amount = $data['amount'];
                    $update = 0;
                    if(array_key_exists('update', $data) &&  $data['update']!=""){
                        $update = $data['update'];
                    }
                    sleep(2);
                    $result = \frontend\actions\App\Withdrawals::User_redeem($uid, $amount,$update);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("app赎回接口---异常 方法：actionUserredeem 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage().'*****'.$e->getTraceAsString(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }


    /**
     * Auther:langxi
     *
     * app提现接口
     */
    public function actionWithdraw()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('amount', $data)) {
                    $uid = $data['uid'];
                    $amount = $data['amount'];
                    $result = \frontend\actions\App\Withdrawals::withdraw($uid, $amount);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("app提现接口---异常 方法：actionWithdraw 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * app用户使用暗码抢加息劵
     */
    public function actionGrabcoupons()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('cid', $data)) {
                    $uid = $data['uid'];
                    $cid = $data['cid'];
                    $result = \frontend\actions\App\AloneMethod::getCoupon($uid, $cid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("app用户使用暗码抢加息劵---异常 方法：actionGrabcoupons 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * app用户使用加息劵
     */
    public function actionUseraise()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('cid', $data)) {
                    $uid = $data['uid'];
                    $cid = $data['cid'];
                    $result = \frontend\actions\App\AloneMethod::useRaise($uid, $cid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("app用户使用加息劵---异常 方法：actionUseraise 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * app项目列表
     */
    public function actionProductlist()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('page', $data) && array_key_exists('page_num', $data)) {
                    $page_num = $data['page_num'];
                    $page = $data['page'];
                    $result = Invest::product_list($page_num, $page);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("app项目列表---异常 方法：actionProductlist 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     * $pid：项目id
     * app获取项目详细信息
     */
    public function actionXproduct()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('pid', $data)) {
                    $pid = $data['pid'];
                    $result = Invest::xproduct($pid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("app获取项目详细信息---异常 方法：actionXproduct 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * app获取项目的可投金额
     */
    public function actionKmoney()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('pid', $data)) {
                    $pid = $data['pid'];
                    $result = Invest::kmoney($pid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("app获取项目的可投金额---异常 方法：actionKmoney 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }


    /**
     * Auther:langxi
     *
     * 获取用户交易记录
     */
    public function actionLog()
    {

        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('num', $data)) {
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $page_num = $data['num'];
                    $result = \frontend\actions\App\AloneMethod::total_log($uid, $page, $page_num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("获取用户交易记录---异常 方法：actionLog 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户手机注册获取验证码
     * @return array|bool|string
     */
    public function actionPhoneregister()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('phone', $data)) {
                    $phone = $data['phone'];
                    $result = member::phonpreg_matchster($phone);

                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户手机注册获取验证码---异常 方法：actionPhoneregister 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户注册登录
     * @return array|string
     */
    public function actionRegister()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('phone', $data) && array_key_exists('pwd', $data) && array_key_exists('confirm_pwd', $data) && array_key_exists('phone_code', $data) && array_key_exists('invite_code', $data) && array_key_exists('from', $data)) {
                    $phone = $data['phone'];
                    $pwd = $data['pwd'];
                    $confirm_pwd = $data['confirm_pwd'];
                    $phone_code = $data['phone_code'];
                    $from = $data['from'];
                    $invite_code = $data['invite_code'];
                    $result = member::register($phone, $pwd, $confirm_pwd, $phone_code, $from, $invite_code);

                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户注册登录---异常 方法：actionRegister 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户登陆
     * @return array|string
     */
    public function actionLogin()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('phone', $data) && array_key_exists('pwd', $data) && array_key_exists('from', $data)) {
                    $phone = $data['phone'];
                    $pwd = $data['pwd'];
                    $from = $data['from'];
                    $sessionkey = '';
                    if(array_key_exists('sessionkey', $data)){
                        $sessionkey = $data['sessionkey'];
                    }
                    $result = member::login($phone, $pwd, $from,$sessionkey);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("登陆异常 方法：actionLogin 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户实名认证接口
     * @return array|bool|string
     */
    public function actionAuthentication()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('name', $data) && array_key_exists('cardno', $data)) {
                    $uid = $data['uid'];
                    $name = $data['name'];
                    $cardno = $data['cardno'];
                    $result = member::authentication($uid, $name, $cardno);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户实名认证接口异常 方法：actionAuthentication 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 判定用户是否实名认证
     * @return array|bool|string
     */
    public static function actionIsauthentic()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = member::isAuthentic($uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("判定用户是否实名认证异常 方法：actionIsauthentic 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**用户注销登陆
     * @return array|string
     */
    public function actionLogout()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = member::logout($uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户注销登陆异常 方法：actionLogout 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户找回密码时，发送验证码
     * @return array|string
     */
    public
    function actionPhonerep()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('phone', $data) && array_key_exists('name', $data) && array_key_exists('idcard', $data)) {
                    $phone = $data['phone'];
                    $name = $data['name'];
                    $idcard = $data['idcard'];
                    $result = member::phoneRep($phone, $name, $idcard);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户找回密码时，发送验证码异常 方法：actionLogout 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }


    /**
     * 用户忘记密码后重置密码行为
     * @return array|string
     */
    public
    function actionResetpassword()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('phone', $data) && array_key_exists('new_pwd', $data) && array_key_exists('rep_pwd', $data) && array_key_exists('phone_code', $data) && array_key_exists('name', $data) && array_key_exists('idcard', $data)) {
                    $phone = $data['phone'];
                    $new_pwd = $data['new_pwd'];
                    $rep_pwd = $data['rep_pwd'];
                    $phone_code = $data['phone_code'];
                    $name = $data['name'];
                    $idcard = $data['idcard'];
                    $result = member::resetPassword($phone, $new_pwd, $rep_pwd, $phone_code, $name, $idcard);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户忘记密码后重置密码行为异常 方法：actionResetpassword 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户修改密码，进行手机号验证
     * @return array|string
     */
    public
    function actionPhonecha()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('phone', $data)) {
                    $phone = $data['phone'];
                    $result = member::phoneCha($phone);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户修改密码，进行手机号验证---异常 方法：actionPhonecha 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户修改密码
     * @return array|string
     */
    public
    function actionChangepassword()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('new_pwd', $data) && array_key_exists('rep_pwd', $data)) {
                    $uid = $data['uid'];
                    $old_pwd = $data['old_pwd'];
                    $new_pwd = $data['new_pwd'];
                    $rep_pwd = $data['rep_pwd'];
                    $result = member::changePassword($uid, $old_pwd, $new_pwd, $rep_pwd);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户修改密码---异常 方法：actionChangepassword 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户绑定银行卡
     * @return array|string
     */
    public
    function actionBindingbankcard()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('bank_account_no', $data) && array_key_exists('phone_no', $data)) {
                    $uid = $data['uid'];
                    $bank_account_no = $data['bank_account_no'];
                    $phone_no = $data['phone_no'];
                    $post_province = '';
                    $post_city = '';
                    $bank_code = '';
                    if(array_key_exists('province', $data) && array_key_exists('city', $data) && array_key_exists('bank_code', $data)) {
                        $post_province = $data['province'];
                        $post_city = $data['city'];
                        $bank_code = $data['bank_code'];
                    }
                    $result = sinapay::bindingBankCard($uid, $bank_account_no, $phone_no,$post_province,$post_city,$bank_code);
                }else{
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户绑定银行卡---异常 方法：actionBindingbankcard 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 绑定银行卡短信确认
     * @return array|string
     */
    public
    function actionBankconfim()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('request_no', $data) && array_key_exists('ticket', $data) && array_key_exists('validate_code', $data)) {
                    $request_no = $data['request_no'];
                    $ticket = $data['ticket'];
                    $validate_code = $data['validate_code'];
                    $result = sinapay::bankCardAdvance($request_no, $ticket, $validate_code);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("绑定银行卡短信确认---异常 方法：actionBankconfim 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取用户绑定银行卡信息
     * @return array|string
     */
    public
    function actionGetbankcard()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = sinapay::getBankCard($uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("获取用户绑定银行卡信息---异常 方法：actionGetbankcard 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }


    /**
     * 用户充值
     * @return array|mixed|string
     */
    public function actionRecharge()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('money', $data)) {
                    $uid = $data['uid'];
                    $amount = $data['money'];
                    $result = sinapay::recharge($uid, $amount);

                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户充值---异常 方法：actionRecharge 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户充值短信确认
     * @return array|mixed|string
     */
    public
    function actionRechargeconfirm()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('out_trade_no', $data) && array_key_exists('ticket', $data) && array_key_exists('validate_code', $data)) {
                    $out_trade_no = $data['out_trade_no'];
                    $ticket = $data['ticket'];
                    $validate_code = $data['validate_code'];
                    $result = sinapay::rechargeComfirm($out_trade_no, $ticket, $validate_code);

                    if ($result['errorNum'] == '0'){
                        $cinfirm = SinaDeposit::find()->where([
                            'out_trade_no' => $out_trade_no,
                            'ticket' => $ticket,
                            //'status' => SinaDeposit::STATUS_CONFIRM
                        ])->one();
                        $uid = $cinfirm->uid;
                        $result = (new sinapay())->findDepositResult($out_trade_no, $uid);
                        $count = 30;
                        while($result['errorNum'] == '2' && $count--){
                            sleep(1);
                            $result = (new sinapay())->findDepositResult($out_trade_no, $uid);
                        }
                    }
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户充值短信确认---异常 方法：actionRechargeconfirm 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户充值
     * @return array|mixed|string
     */
    public function actionRechargeresult()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('out_trade_no', $data) && array_key_exists('uid', $data)) {
                    $out_trade_no = $data['out_trade_no'];
                    $uid = $data['uid'];
                    $result = (new sinapay())->findDepositResult($out_trade_no, $uid);

                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户充值查询---异常 方法：actionRechargeresult 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取用户的详细信息
     * @return array|null|string|\yii\db\ActiveRecord
     */
    public
    function actionGetuserinfo()
    {
        $result = App::AppGet();

        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = sinapay::getUserInfo($uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("获取用户的详细信息---异常 方法：actionGetuserinfo 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取网站总的投资数据
     * @return array|string
     * @throws \yii\base\ErrorException
     */
    public
    function actionSeetotal()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = \frontend\actions\App\AloneMethod::see_total();
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("获取网站总的投资数据---异常 方法：actionSeetotal 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 获取用户的昨日收益，再投金额，再投收益，账户余额
     */
    public
    function actionUsercollect()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = \frontend\actions\App\AloneMethod::user_collect($uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("获取用户的昨日收益，再投金额，再投收益，账户余额---异常 方法：actionUsercollect 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 用户收益记录
     */
    public
    function actionProfitlog()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('num', $data)) {
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = \frontend\actions\App\AloneMethod::profit_log($uid, $page, $num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户收益记录---异常 方法：actionProfitlog 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 用户充值记录
     */
    public
    function actionRechargelog()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('num', $data)) {
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = \frontend\actions\App\AloneMethod::recharge_log($uid, $page, $num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户充值记录---异常 方法：actionRechargelog 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }


    /**
     * Auther:langxi
     *
     * 用户投资记录
     */
    public
    function actionInvestlog()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('num', $data)) {
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = \frontend\actions\App\AloneMethod::invest_log($uid, $page, $num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户投资记录---异常 方法：actionInvestlog 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 用户提现记录
     */
    public
    function actionWithdrawalslog()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('num', $data)) {
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = \frontend\actions\App\AloneMethod::withdrawals_log($uid, $page, $num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户提现记录---异常 方法：actionWithdrawalslog 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 用户赎回记录
     */
    public
    function actionRedeemlog()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('num', $data)) {
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = \frontend\actions\App\AloneMethod::redeem_log($uid, $page, $num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("用户赎回记录---异常 方法：actionRedeemlog 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 消息中心
     */
    public function actionMcentor()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('page', $data) && array_key_exists('num', $data)) {
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = \frontend\actions\App\AloneMethod::m_centor($page, $num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("消息中心---异常 方法：actionMcentor 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 反馈意见
     */
    public function actionFeedback()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('content',$data)) {
                    $uid = $data['uid'];
                    $content = $data['content'];
                    $result = \frontend\actions\App\AloneMethod::feedback($uid, $content);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error("反馈意见---异常 方法：actionFeedback 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户是否签到
     * @return array|string
     */
    public function actionIssigntoday()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = member::is_sign_today($uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户是否签到---异常 方法：actionIssigntoday 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户签到
     * @return array|string
     */
    public function actionSignin()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('from', $data)) {
                    $uid = $data['uid'];
                    $from = $data['from'];
                    $result = member::signIn($uid, $from);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户签到---异常 方法：actionSignin 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取用户签到记录
     * @return array|string
     */
    public function actionGetusersignin()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page_no', $data) && array_key_exists('page_size', $data)) {
                    $uid = $data['uid'];
                    $page_no = $data['page_no'];
                    $page_size = $data['page_size'];
                    $result = member::get_user_sign_in($uid, $page_no, $page_size);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("获取用户签到记录---异常 方法：actionGetusersignin 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取用户昨日签到收益
     * @return array|string
     */
    public function actionGetyesterdayuser()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = member::get_yesterday_user($uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("获取用户昨日签到收益---异常 方法：actionGetyesterdayuser 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取网站昨日签到情况
     * @return array|string
     */
    public function actionGetyesterdaysignin()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $result = member::get_yesterday_sign_in();
            }
        } catch (ErrorException $e) {
            Yii::error("获取网站昨日签到情况---异常 方法：actionGetyesterdaysignin 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取网站今日签到情况
     * @return array|string
     */
    public function actionGettodaysignin()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $result = member::get_today_sign_in();
            }
        } catch (ErrorException $e) {
            Yii::error("获取网站今日签到情况---异常 方法：actionGettodaysignin 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取网站签到情况
     * @return array|string
     */
    public function actionGetsignin(){

        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $result = member::get_sign_in();
            }
        } catch (ErrorException $e) {
            Yii::error("获取网站签到情况---异常 方法：actionGetsignin 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户阅读文章--添加阅读记录
     * @return array|string
     */
    public function actionReadingloog()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('aid', $data)) {
                    $uid = $data['uid'];
                    $aid = $data['aid'];
                    $result = App\AloneMethod::reading_log($aid, $uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户阅读文章--添加阅读记录---异常 方法：actionReadingloog 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 判定用户是否阅读了消息
     * @return array|string
     */
    public static function actionIsreading()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('aid', $data)) {
                    $uid = $data['uid'];
                    $aid = $data['aid'];
                    $result = App\AloneMethod::is_reading($aid, $uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("判定用户是否阅读了消息---异常 方法：actionIsreading 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取用户消息中心是否有消息
     * @return array|string
     */
    public static function actionIsmsg()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $msa_arr = $data['msa_arr'];
                    $result = App\AloneMethod::is_msg($msa_arr, $uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("获取网站今日签到情况---异常 方法：actionGettodaysignin 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户忽略全部消息
     * @return array|string
     */
    public static function actionIgnoreall()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $msa_arr = $data['msa_arr'];
                    $result = App\AloneMethod::ignore_all($msa_arr, $uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户忽略全部消息---异常 方法：actionIgnoreall 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 判定用户是否存在消息
     * @return array|string
     */
    public static function actionIsexitmsg()
    {
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $result = App\AloneMethod::is_exit_msg($uid);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户忽略全部消息---异常 方法：actionIgnoreall 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取用户消息中心状况
     * @return array|string
     */
    public static function actionUsermsgcentor(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('uid', $data) && array_key_exists('uid', $data)) {
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = App\AloneMethod::user_msg_centor($uid,$page,$num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error("用户忽略全部消息---异常 方法：actionIgnoreall 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取体验金列表
     * @return array|string
     */
    public static function actionGetexpgoldlog(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('num', $data)) {
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = App\AloneMethod::experience_gold_log($uid,$page,$num);
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }
            }
        } catch (ErrorException $e) {
            Yii::error(" 获取体验金列表---异常 方法：actionGetexpgoldlog 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }


    /**
     * 获取轮播图
     * @return array|string
     */
    public static function actionGetlunbo(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $result = App\AloneMethod::cms_lunbo();
            }
        } catch (ErrorException $e) {
            Yii::error(" 获取轮播图---异常 方法：actionGetlunbo 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取启动页
     * @return array|string
     */
    public static function actionGetstart(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $result = App\AloneMethod::cms_qidong();
            }
        } catch (ErrorException $e) {
            Yii::error(" 获取启动页---异常 方法：actionGetstart 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取活动红包列表
     * @return array|string
     */
    public static function actionGetradlist(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('num', $data)){
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['num'];
                    $result = member::get_rad_list($uid,$page,$num);
                }else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error(" 获取轮播图---异常 方法：actionGetradlist 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取用户当前红包金额
     * @return array|string
     */
    public static function actionGetuserredpacket(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)){
                    $uid = $data['uid'];
                    $result = member::get_user_red_packet($uid);
                }else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error(" 获取轮播图---异常 方法：actionGetuserredpacket 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 用户充值时做限定
     * @return array|string
     */
    public static function actionChecktodaydeposit(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data)){
                    $uid = $data['uid'];
                    $result = sinapay::checktodaydeposit($uid);
                }else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error(" 用户充值时做限定---异常 方法：actionChecktodaydeposit 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }


    /**
     * 获取用户关系列表
     * @return array|string
     */
    public static function actionGetrecommendrelation(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('uid', $data) && array_key_exists('page', $data) && array_key_exists('page_size', $data)){
                    $uid = $data['uid'];
                    $page = $data['page'];
                    $num = $data['page_size'];
                    $result = AloneMethod::get_recommend_relation($uid,$page,$num);
                }else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error(" 获取用户关系列表---异常 方法：actionGetrecommendrelation 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 获取单个文件
     * @return array|string
     */
    public static function actionGetsinglearticle(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('title', $data)){
                    $title = $data['title'];
                    $result = AloneMethod::get_single_article($title);
                }else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error(" 获取单个文件---异常 方法：actionGetsinglearticle 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }

    /**
     * 通过分类获取文章列表
     * @return array|string
     */
    public static function actionGetcategoryarticles(){
        $result = App::AppGet();
        try {
            if ($result['errorNum']) {
                $result = array(
                    'errorNum' => $result['errorNum'],
                    'errorMsg' => $result['errorMsg'],
                    'data' => null,
                );
            } else {
                $data = $result['data'];
                if (array_key_exists('title', $data)){
                    $title = $data['title'];
                    $result = AloneMethod::get_category_article($title);
                }else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '参数错误',
                        'data' => null,
                    );
                }

            }
        } catch (ErrorException $e) {
            Yii::error(" 通过分类获取文章列表---异常 方法：actionGetcategoryarticles 参数：" . json_encode($result['data']), "app");
            Yii::trace($e->getMessage(), "app");
            $result = array(
                'errorNum' => '7',
                'errorMsg' => '服务器异常，请联系管理员',
                'data' => null,
            );
        }
        $result = App::AppReturn($result);
        return $result;
    }
}