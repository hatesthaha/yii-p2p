<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/30
 * Time: 11:48
 */

namespace frontend\actions\app;

use common\models\base\activity\ActivityLog;
use common\models\base\activity\HoldActivity;
use common\models\base\activity\RaiseCard;
use common\models\base\asset\AssetLog;
use common\models\base\experience\Gold;
use common\models\base\experience\Rule;
use common\models\base\session\Sessionkey;
use common\models\base\ucenter\Log;
use common\models\invation\InvitationCode;
use common\models\post\SignIn;
//use frontend\actions\AloneMethod;
use frontend\actions\app\Port;
use frontend\actions\sinapay;
use Yii;
use common\models\base\asset\Info;
use common\models\UcenterMember;
use yii\base\Component;
use yii\base\ErrorException;

class member extends Component
{

    //用户登陆活动状态
    const  STATUS_ACTIVE = 1;
    //用户注销登陆
    const  STATUS_DELETE = -1;

    //用户确认登录
    const  LOG_CONFIM = 0;
    //用户登陆成功
    const  LOG_SUSSESS = 1;

    /**
     * 判定用户手机号是否存在
     * @param $phone 手机号
     * @return bool
     */
    public static function phoneIsRegister($phone)
    {
        $res = UcenterMember::findOne([
            'phone' => $phone,
            'username' => $phone
        ]);
        if ($res) {
            return $res['id'];
        } else {
            return false;
        }
    }


    /**
     * 注册时发送手机验证码
     * @param $phone 手机号
     * @return array
     */
    public static function phonpreg_matchster($phone)
    {
        if ($phone == "") {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '手机号不能为空',
                'data' => null
            );
            return $return;
        } elseif (!preg_match("/1[34578]{1}\d{9}$/", $phone)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请输入正确手机号',
                'data' => null
            );
            return $return;
        } elseif (self::phoneIsRegister($phone)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '此手机号已注册，请直接登录',
                'data' => null
            );
            return $return;
        } else {
            //短信接口1
//            $res = Port::ValidatePhone($phone);
            //短息接口2
            $res = Port::ValidatePhone2($phone,'1');
            if (!$res['errorNum']) {
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            } else {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $res['errorMsg'],
                    'data' => null
                );
                return $return;
            }
        }
    }

    /**
     * 新用户注册
     * @param $phone 手机号
     * @param $pwd  密码
     * @param $confirm_pwd 重复密码
     * @param $phone_code 手机验证码
     * @param $from 用户来源
     * @param string $invite_code 邀请码
     * @return array
     */
    public static function register($phone, $pwd, $confirm_pwd, $phone_code, $from, $invite_code = '')
    {
        //是否必须邀请注册
        $must_invite = 0;
        //测试阶段邀请码
        $test_invite = 0;
        //开启推广大师
        $recommend_the_master = 1;
        //判定手机号是否注册
        $flag = self::phoneIsRegister($phone);
        if ($flag) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '此手机号已注册，请直接登录',
                'data' => null
            );
            return $return;
        } elseif ($pwd != $confirm_pwd) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '两次密码不一致',
                'data' => null
            );
            return $return;
        } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $pwd)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                'data' => null
            );
            return $return;
        }
        //判定注册来源
        $array_from = array('2', '3', '4');
        if (!in_array($from, $array_from)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '注册来源非法',
                'data' => null
            );
            return $return;
        }
        //邀请者id
        $invitation_id = 0;
        //必须进行邀请
        if ($must_invite) {
            if ($invite_code == "") {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '必须填写邀请码',
                    'data' => null
                );
                return $return;
            } else {
                if ($test_invite) {
                    //使用测试邀请
                    $flag = self::verify_code($invite_code);
                    //验证码通过
                    if (!$flag) {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '邀请码错误',
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    if ($invite_code != "" && $invite_code != "1Cr07Yyk") {
                        $user = UcenterMember::findOne([
                            'invitation_code' => $invite_code,
                        ]);
                        if ($user !== null) {
                            $invitation_id = $user['id'];
                            //增加好友注册体验金
                            self::give_experience_gold(4, $user['id']);
                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '测试阶段必须有正确邀请才能注册',
                                'data' => null
                            );
                            return $return;
                        }
                    } else {
                        $invitation_id = 0;
                    }
                }
            }
        } else {
            if ($invite_code != "") {
                $user = UcenterMember::findOne([
                    'invitation_code' => $invite_code,
                ]);
                if ($user) {
                    $invitation_id = $user['id'];
                    //增加好友注册体验金
                    self::give_experience_gold(4, $user['id']);
                }
            }
        }
        //手机短息验证码验证
        $check = Port::checkPhnoe($phone, $phone_code);
        $session_key = "";
        //生成app的密码
        $app_pwd = md5(sha1($pwd) . time());
        // 根据ip获取地区
        $area = self::get_area(Yii::$app->request->userIp);
        $area = $area ? $area : '地球';
        if (!$check['errorNum']) {
            //事物回滚
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $user = new UcenterMember();
                $user->username = $phone;
                $user->phone = $phone;
                $user->setPassword(trim($pwd));
                $user->create_ip = Yii::$app->request->userIp;
                $user->create_area = $area;
                $user->create_channel = (int)$from;
                $user->invitation_code = self::random();
                $user->invitation_id = (int)$invitation_id;
                $user->status = UcenterMember::STATUS_ACTIVE;
                $user->lock = UcenterMember::TYPE_UNLOCK;
                //app密码设定
                $user->app_pwd = $app_pwd;
                ///
                $user->generateAuthKey();
                if ($user->save()) {
                    //生成邀请码
                    $new_uid = $user->id;
                    $invitation_code = 'v' . $new_uid * 99;
                    $user->invitation_code = $invitation_code;
                    $user->save();
                    //初始换用户账户
                    $asset_info = new Info();
                    $asset_info->member_id = $user['id'];
                    $asset_info->balance = 0;
                    $asset_info->freeze = 0;
                    if ($asset_info->save()) {
                        $uid = $user['id'];
                        if ($test_invite) {
                            //使用测试阶段验证码
                            $flag = self::verify_code($invite_code);
                            $invite_flag = self::use_code($flag, $uid);
                            if (!$invite_flag) {
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => '注册失败',
                                    'data' => null
                                );
                                return $return;
                            }
                        }
                        if ($recommend_the_master) {
                            //推荐大师活动开启了
                            //用户注册--增加数据表
                            $produce = \frontend\actions\AloneMethod::produce_recommend($uid);
                            if ($produce['errorNum'] == '1') {
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => '推荐大师活动错误',
                                    'data' => null
                                );
                                return $return;
                            }
                        }
                        //注册动作完成---进行登录操作
                        $log = new Log();
                        $log->member_id = $uid;
                        $log->login_ip = Yii::$app->request->userIp;
                        $log->login_time = time();
                        $log->login_area = $area;
                        $log->status = self::LOG_CONFIM;
                        $res = $log->save();
                        if ($res) {
                            $session_key = self::verification($uid) . '--' . $from;
                            $session = new Sessionkey();
                            $session->uid = $uid;
                            $session->sessionkey = $session_key;
                            $session->status = self::STATUS_ACTIVE;
                            $res = $session->save();
                            if ($res) {
                                $log->status = self::LOG_SUSSESS;
                                $log->save();
                                //新用户注册送体验金---区分推荐用户和非推荐用户
                                if ($invitation_id == 0) {
                                    self::give_experience_gold(1, $user['id']);
                                } else {
                                    self::give_experience_gold(7, $user['id']);
                                }
                            } else {
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => '登陆失败',
                                    'data' => null
                                );
                                return $return;
                            }

                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '登陆记录失败',
                                'data' => null
                            );
                            return $return;
                        }
                    } else {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '账户初始化失败',
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '注册失败',
                        'data' => null
                    );
                    return $return;
                }
                $transaction->commit();

                //事务成功提交后返回数据
                $data = array(
                    'sessionkey' => $session_key,
                    'balance' => 0,
                    'phone' => $phone,
                    'app_pwd' => $app_pwd
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;

            } catch (\Exception $e) {
                $transaction->rollBack();
                $remark = $e->getMessage();
                $result = array('errorNum' => '1', 'errorMsg' => $remark, 'data' => null);
                return $result;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $check['errorMsg'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 微信用户注册
     * @param $phone 手机号
     * @param $pwd  密码
     * @param $confirm_pwd 重复密码
     * @param $phone_code 手机验证码
     * @param $from 用户来源
     * @param string $invite_code 邀请码
     * @return array
     */
    public static function wechatregister($phone, $pwd, $confirm_pwd, $phone_code, $from, $invite_code = '')
    {
        //是否必须邀请注册
        $must_invite = 0;
        //测试阶段邀请码
        $test_invite = 0;
        //判定手机号是否注册
        $flag = self::phoneIsRegister($phone);
        if ($flag) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '此手机号已注册，请直接登录',
                'data' => null
            );
            return $return;
        } elseif ($pwd != $confirm_pwd) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '两次密码不一致',
                'data' => null
            );
            return $return;
        } elseif (!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/', $pwd)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                'data' => null
            );
            return $return;
        }
        //判定注册来源
        $array_from = array('2', '3', '4');
        if (!in_array($from, $array_from)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '注册来源非法',
                'data' => null
            );
            return $return;
        }
        //邀请者id
        $invitation_id = 0;
        //必须进行邀请
        if ($must_invite) {
            if ($invite_code == "") {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '必须填写邀请码',
                    'data' => null
                );
                return $return;
            } else {
                if ($test_invite) {
                    //使用测试邀请
                    $flag = self::verify_code($invite_code);
                    //验证码通过
                    if (!$flag) {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '邀请码错误',
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    if ($invite_code != "" && $invite_code != "1Cr07Yyk") {
                        $user = UcenterMember::findOne([
                            'invitation_code' => $invite_code,
                        ]);
                        if ($user) {
                            $invitation_id = $user['id'];
                            //增加好友注册体验金
                            self::give_experience_gold(4, $user['id']);
                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '测试阶段必须有正确邀请才能注册',
                                'data' => null
                            );
                            return $return;
                        }
                    } else {
                        $invitation_id = '0';
                    }
                }
            }
        } else {
            if ($invite_code != "") {
                $user = UcenterMember::findOne([
                    'invitation_code' => $invite_code,
                ]);
                if ($user != null) {
                    $invitation_id = $user['id'];
                }
            }
        }
        //检查短息验证码
        $check = Port::checkPhnoe($phone, $phone_code);
        //生成app的密码
        $app_pwd = md5(sha1($pwd) . time());
        // 根据ip获取地区
        $area = self::get_area(Yii::$app->request->userIp);
        $area = $area ? $area : '地球';
        if (!$check['errorNum']) {
            //事物回滚
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $user = new UcenterMember();
                $user->username = $phone;
                $user->phone = $phone;
                $user->setPassword(trim($pwd));
                $user->create_ip = Yii::$app->request->userIp;
                $user->create_area = $area;
                $user->create_channel = (int)$from;
                $user->invitation_code = self::random();
                $user->invitation_id = (int)$invitation_id;
                $user->status = UcenterMember::STATUS_ACTIVE;
                $user->lock = UcenterMember::TYPE_UNLOCK;
                //app密码设定
                $user->app_pwd = $app_pwd;
                ///
                $user->generateAuthKey();
                if ($user->save()) {
                    //生成邀请码
                    $new_uid = $user->id;
                    $invitation_code = 'v' . $new_uid * 99;
                    $user->invitation_code = $invitation_code;
                    $user->save();
                    //初始换用户账户
                    $asset_info = new Info();
                    $asset_info->member_id = $user['id'];
                    $asset_info->balance = 0;
                    $asset_info->freeze = 0;
                    if ($asset_info->save()) {
                        $uid = $user['id'];
                        //注册动作完成---进行登录操作
                        $log = new Log();
                        $log->member_id = $uid;
                        $log->login_ip = Yii::$app->request->userIp;
                        $log->login_time = time();
                        $log->login_area = $area;
                        $log->status = self::LOG_CONFIM;
                        $res = $log->save();
                        if ($res) {
                            //增加注册体验金
                            self::give_experience_gold(1, $user['id']);
                        }
                    } else {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '账户初始化失败',
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '注册失败',
                        'data' => null
                    );
                    return $return;
                }

                $transaction->commit();
                //事务成功提交后返回数据
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;

            } catch (\Exception $e) {
                $transaction->rollBack();
                $remark = $e->getMessage();
                $result = array('errorNum' => '1', 'errorMsg' => $remark, 'data' => null);
                return $result;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $check['errorMsg'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户登陆
     * @param $phone
     * @param $pwd
     * @param $from
     * @return array
     */
    public static function login($phone, $pwd, $from,$sessionkey = '')
    {
        $is_exist = self::phoneIsRegister($phone);
        if ($is_exist) {
            $res = UcenterMember::findOne([
                'phone' => $phone
            ]);
            if ($res['lock'] == UcenterMember::TYPE_BLOCK) {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '账户已被加入黑名单',
                    'data' => null
                );
                return $return;
            } elseif ($res['lock'] == UcenterMember::TYPE_LOCK) {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '账户已被锁定',
                    'data' => null
                );
                return $return;
            } else {
                //判定注册来源
                $array_from = array('2', '3', '4');
                if (!in_array($from, $array_from)) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '登陆来源非法',
                        'data' => null
                    );
                    return $return;
                }
                $uid = $res['id'];
                $session_key = self::verification($uid) . '--' . $from;
                //app---验证用户密码
                $app_pwd = $res['app_pwd'];
                if ($app_pwd == $pwd) {
                    $flag = true;
                } else {
                    $flag = Yii::$app->security->validatePassword($pwd, $res['password_hash']);
                }
                if ($flag) {
                    // 根据ip获取地区
                    $area = self::get_area(Yii::$app->request->userIp);
                    $area = $area ? $area : '地球';
                    $log = new Log();
                    $log->member_id = $uid;
                    $log->login_ip = Yii::$app->request->userIp;
                    $log->login_area = $area;
                    $log->login_time = time();
                    $log->status = self::LOG_CONFIM;
                    $res = $log->save();
                    if ($res) {
                        // 用户还处于登陆状态
                        $key = Sessionkey::find()->where([
                            'uid' => $uid,
                            'status' => self::STATUS_ACTIVE
                        ])->one();
                        if ($key) {
                            //TODO--自动登录问题修复
                            $db_sessionkey = $key->sessionkey;
                            if($db_sessionkey != $sessionkey){
                                $key->status = self::STATUS_DELETE;
                                if (!$key->save()) {
                                    $return = array(
                                        'errorNum' => '1',
                                        'errorMsg' => '其他终端下线失败',
                                        'data' => null
                                    );
                                    return $return;
                                }
                            }
                            //原来代码
//                            $key->status = self::STATUS_DELETE;
//                            if (!$key->save()) {
//                                $return = array(
//                                    'errorNum' => '1',
//                                    'errorMsg' => '其他终端下线失败',
//                                    'data' => null
//                                );
//                                return $return;
//                            }
                        }
                        $session = new Sessionkey();
                        $session->uid = $uid;
                        $session->sessionkey = $session_key;
                        $session->status = self::STATUS_ACTIVE;
                        $res = $session->save();
                        if ($res) {
                            $log->status = self::LOG_SUSSESS;
                            $log->save();
                            $info = Info::find()->where([
                                'member_id' => $uid
                            ])->one();
                            $data = array(
                                'sessionkey' => $session_key,
                                'balance' => $info['balance'],
                                'phone' => $phone,
                                'app_pwd' => $app_pwd
                            );
                            $return = array(
                                'errorNum' => '0',
                                'errorMsg' => 'success',
                                'data' => $data
                            );
                            return $return;
                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '登陆失败',
                                'data' => null
                            );
                            return $return;
                        }

                    } else {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '登陆记录失败',
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '登陆密码错误',
                        'data' => null
                    );
                    return $return;
                }
            }

        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户退出登陆
     * @param $uid
     * @return array
     */
    public static function logout($uid)
    {
        $res = Sessionkey::find()->where([
            'uid' => $uid,
            'status' => self::STATUS_ACTIVE
        ])->one();
        if ($res) {
            $res->status = self::STATUS_DELETE;
            if ($res->save()) {
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            } else {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '用户退出失败',
                    'data' => null
                );
                return $return;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }


    }

    /**用户实名认证接口
     * @param $uid 用户id
     * @param $name 实名信息
     * @param $cardno 身份证件号
     * @return bool
     * @throws ErrorException
     */
    public static function authentication($uid, $name, $cardno)
    {

        $iscard = self::idcardIsAuthentic($cardno);
        $is = self::isAuthentic($uid);
        if ($iscard || !$is['errorNum']) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '已经存在实名认证信息',
                'data' => null
            );
            return $return;
        } else {
            //调用验证接口
//            $res = Port::authentication($uid,$name,$cardno);
            $res = sinapay::authentication($uid, $name, $cardno);
            if (!$res['errorNum']) {
                $member = UcenterMember::findOne($uid);
                $member->idcard = $cardno;
                $member->real_name = $name;
                $member->status = UcenterMember::STATUS_REAL;
                if ($member->save()) {
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => null
                    );
                    return $return;
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '实名信息存储失败',
                        'data' => null
                    );
                    return $return;
                }
            } else {
                $errorMsg = $res['errorMsg'];
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $errorMsg,
                    'data' => null
                );
                return $return;
            }
        }
    }

    /**
     * 判定用户是否实名认证
     * @param $uid
     * @return bool
     */
    public static function isAuthentic($uid)
    {
        $member = UcenterMember::findOne($uid);
        if ($member) {
            if ($member->idcard && $member->real_name) {
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => array(
                        'real_name' => $member->real_name,
                        'idcard' => $member->idcard
                    )
                );
                return $return;
            } else {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => "未实名认证",
                    'data' => null
                );
                return $return;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => "用户不存在",
                'data' => null
            );
            return $return;
        }

    }

    /**判定身份证是否已认证
     * @param $idcard
     * @return bool
     */
    public static function idcardIsAuthentic($idcard)
    {
        $member = UcenterMember::findOne([
            'idcard' => $idcard,
        ]);
        if ($member) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 用户修改密码发送手机验证码
     * @param $phone 手机号
     * @return array
     */
    public static function phoneCha($phone)
    {
        $flag = self::phoneIsRegister($phone);
        if ($flag) {
//            $res = Port::ValidatePhone($phone);
            //todo
            $res = Port::ValidatePhone2($phone,'5');//修改密码
            if (!$res['errorNum']) {
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            } else {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $res['errorMsg'],
                    'data' => null
                );
                return $return;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => "手机号未注册",
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户修改密码
     * @param $uid
     * @param $old_pwd
     * @param $new_pwd
     * @param $rep_pwd
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function changePassword($uid, $old_pwd, $new_pwd, $rep_pwd)
    {
        $member = UcenterMember::findOne($uid);
        if ($member) {
            $flag = Yii::$app->security->validatePassword($old_pwd, $member['password_hash']);
            if ($flag) {
                if ($new_pwd != $rep_pwd) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '两次密码不一致',
                        'data' => null
                    );
                    return $return;
                } elseif (strlen($new_pwd) < 5) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '密码长度不能小于6位',
                        'data' => null
                    );
                    return $return;
                } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $new_pwd)) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                        'data' => null
                    );
                    return $return;
                } else {
                    //修改密码
                    $app_pwd = md5(sha1($new_pwd) . time());
                    $hash_pwd = Yii::$app->security->generatePasswordHash($new_pwd);
                    $member->password_hash = $hash_pwd;
                    $member->app_pwd = $app_pwd;
                    $res = $member->save();
                    if ($res) {
                        // 注销用户sessionkey--让用户重新登陆
                        $key = Sessionkey::find()->where([
                            'uid' => $uid,
                            'status' => self::STATUS_ACTIVE
                        ])->one();
                        if ($key) {
                            $key->status = self::STATUS_DELETE;
                            if ($key->save()) {
                                $return = array(
                                    'errorNum' => '0',
                                    'errorMsg' => "success",
                                    'data' => null
                                );
                                return $return;
                            } else {
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => "用户下线失败",
                                    'data' => null
                                );
                                return $return;
                            }
                        }
                    } else {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => "修改密码失败",
                            'data' => null
                        );
                        return $return;
                    }

                }
            } else {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => "原密码错误",
                    'data' => null
                );
                return $return;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => "用户不存在",
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户用于找回密码时，进行的发送手机验证码操作
     * @param $phone 注册手机号
     * @param string $name 账号的认证姓名
     * @param string $idcard 认证的身份证号
     * @return array
     */
    public static function phoneRep($phone, $name = "", $idcard = "")
    {
        $is_reg = self::phoneIsRegister($phone);
        if ($is_reg) {
            $is_aut = self::isAuthentic($is_reg);
            //是实名认证用户
            if (!$is_aut['errorNum']) {
                // 验证实名信息
                if ($is_aut['data']['real_name'] == $name && $is_aut['data']['idcard'] == $idcard) {
                    //发送验证码
//                    $res = Port::ValidatePhone($phone);
                    $res = Port::ValidatePhone2($phone,'4');//重置密码
                    if (!$res['errorNum']) {
                        $return = array(
                            'errorNum' => '0',
                            'errorMsg' => 'success',
                            'data' => null
                        );
                        return $return;
                    } else {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => $res['errorMsg'],
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => "请先填写实名信息",
                        'data' => null
                    );
                    return $return;
                }
            } else {
//                $res = Port::ValidatePhone($phone);
                $res = Port::ValidatePhone2($phone,'4');//重置密码
                if (!$res['errorNum']) {
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => null
                    );
                    return $return;
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $res['errorMsg'],
                        'data' => null
                    );
                    return $return;
                }
            }

        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => "手机号不存在",
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户忘记密码，需要重置密码
     * @param $phone 用户手机号
     * @param $new_pwd 新密码
     * @param $rep_pwd 重复密码
     * @param $phone_code 手机验证码
     * @param string $name 真实姓名
     * @param string $idcard 身份证号
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function resetPassword($phone, $new_pwd, $rep_pwd, $phone_code, $name = "", $idcard = "")
    {
        $is_reg = self::phoneIsRegister($phone);

        if ($is_reg) {
            $is_aut = self::isAuthentic($is_reg);
            //用户进行了实名认证
            if (!$is_aut['errorNum']) {
                //实名信息验证通过
                if ($is_aut['data']['real_name'] == $name && $is_aut['data']['idcard'] == $idcard) {
                    //验证手机验证码

                    if ($new_pwd != $rep_pwd) {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '两次密码不一致',
                            'data' => null
                        );
                        return $return;
                    } elseif (strlen($new_pwd) < 5) {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '密码长度不能小于6位',
                            'data' => null
                        );
                        return $return;
                    } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $new_pwd)) {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                            'data' => null
                        );
                        return $return;
                    } else {
                        $check = Port::checkPhnoe($phone, $phone_code);

                        if ($check['errorNum']) {
                            return $check;
                        }
                        $member = UcenterMember::findOne($is_reg);
                        if ($member) {
                            $app_pwd = md5(sha1($new_pwd) . time());
                            $hash_pwd = Yii::$app->security->generatePasswordHash($new_pwd);
                            $member->password_hash = $hash_pwd;
                            $member->app_pwd = $app_pwd;
                            $res = $member->save();
                            //修改密码成功---对登陆状态做判定
                            if ($res) {
                                $key = Sessionkey::find()->where([
                                    'uid' => $is_reg,
                                    'status' => self::STATUS_ACTIVE
                                ])->one();
                                //如果用户是在登陆状态进行的操作-----让用户重新登陆
                                if ($key) {
                                    $key->status = self::STATUS_DELETE;
                                    if ($key->save()) {
                                        $return = array(
                                            'errorNum' => '0',
                                            'errorMsg' => "success",
                                            'data' => null
                                        );
                                        return $return;
                                    } else {
                                        $return = array(
                                            'errorNum' => '1',
                                            'errorMsg' => "用户下线失败",
                                            'data' => null
                                        );
                                        return $return;
                                    }
                                }//用户在未登录下操作
                                else {
                                    $return = array(
                                        'errorNum' => '0',
                                        'errorMsg' => "success",
                                        'data' => null
                                    );
                                    return $return;
                                }
                            } else {
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => "修改密码失败",
                                    'data' => null
                                );
                                return $return;
                            }
                        }
                    }
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => "已实名认证用户需先填写实名信息",
                        'data' => null
                    );
                    return $return;
                }
            }//未实名认证用户
            else {
                if ($new_pwd != $rep_pwd) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '两次密码不一致',
                        'data' => null
                    );
                    return $return;
                } elseif (strlen($new_pwd) < 5) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '密码长度不能小于6位',
                        'data' => null
                    );
                    return $return;
                } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $new_pwd)) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                        'data' => null
                    );
                    return $return;
                } else {
                    $check = Port::checkPhnoe($phone, $phone_code);
                    if ($check['errorNum']) {
                        return $check;
                    }
                    $member = UcenterMember::findOne($is_reg);
                    if ($member) {
                        $app_pwd = md5(sha1($new_pwd) . time());
                        $hash_pwd = Yii::$app->security->generatePasswordHash($new_pwd);
                        $member->password_hash = $hash_pwd;
                        $member->app_pwd = $app_pwd;
                        $res = $member->save();
                        //修改密码成功---对登陆状态做判定
                        if ($res) {
                            $key = Sessionkey::find()->where([
                                'uid' => $is_reg,
                                'status' => self::STATUS_ACTIVE
                            ])->one();
                            //如果用户是在登陆状态进行的操作-----让用户重新登陆
                            if ($key) {
                                $key->status = self::STATUS_DELETE;
                                if ($key->save()) {
                                    $return = array(
                                        'errorNum' => '0',
                                        'errorMsg' => "success",
                                        'data' => null
                                    );
                                    return $return;
                                } else {
                                    $return = array(
                                        'errorNum' => '1',
                                        'errorMsg' => "用户下线失败",
                                        'data' => null
                                    );
                                    return $return;
                                }
                            }//用户在未登录下操作
                            else {
                                $return = array(
                                    'errorNum' => '0',
                                    'errorMsg' => "success",
                                    'data' => null
                                );
                                return $return;
                            }
                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => "修改密码失败",
                                'data' => null
                            );
                            return $return;
                        }
                    }
                }
            }

        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => "用户不存在",
                'data' => null
            );
            return $return;
        }

    }

    /**
     * 微信端用户登陆
     * @param $phone
     * @param $pwd
     * @param $from
     * @return array
     */
    public static function login_weixin($phone, $pwd, $from)
    {
        $is_exist = self::phoneIsRegister($phone);
        if ($is_exist) {
            $res = UcenterMember::findOne([
                'phone' => $phone
            ]);
            if ($res['lock'] == UcenterMember::TYPE_BLOCK) {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '账户已被加入黑名单',
                    'data' => null
                );
                return $return;
            } elseif ($res['lock'] == UcenterMember::TYPE_LOCK) {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '账户已被锁定',
                    'data' => null
                );
                return $return;
            } else {
                //判定登录来源
                $array_from = array('2', '3', '4');
                if (!in_array($from, $array_from)) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '登陆来源非法',
                        'data' => null
                    );
                    return $return;
                }
                $uid = $res['id'];
                //验证用户的密码
                $flag = Yii::$app->security->validatePassword($pwd, $res['password_hash']);
                if ($flag) {
                    // 根据ip获取地区
                    $area = self::get_area(Yii::$app->request->userIp);
                    $area = !empty($area) ? $area : '地球';
                    //记录登录日志
                    $log = new Log();
                    $log->member_id = $uid;
                    $log->login_ip = Yii::$app->request->userIp;
                    $log->login_area = $area;
                    $log->login_time = time();
                    $log->status = self::LOG_CONFIM;
                    $res = $log->save();
                    if ($res) {
                            $return = array(
                                'errorNum' => '0',
                                'errorMsg' => 'success',
                                'data' => null
                            );
                            return $return;
                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '登陆失败',
                                'data' => null
                            );
                            return $return;
                        }

                    }else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '用户名或密码错误',
                        'data' => null
                    );
                    return $return;
                }
            }

        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户名或密码错误',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户忘记密码，需要重置密码
     * @param $phone 用户手机号
     * @param $new_pwd 新密码
     * @param $rep_pwd 重复密码
     * @param $phone_code 手机验证码
     * @param string $name 真实姓名
     * @param string $idcard 身份证号
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function resetwechatPassword($phone, $new_pwd, $rep_pwd, $phone_code, $name = "", $idcard = "")
    {
        $is_reg = self::phoneIsRegister($phone);

        if ($is_reg) {
            $is_aut = self::isAuthentic($is_reg);
            //用户进行了实名认证
            if (!$is_aut['errorNum']) {

                //验证手机验证码

                if ($new_pwd != $rep_pwd) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '两次密码不一致',
                        'data' => null
                    );
                    return $return;
                } elseif (strlen($new_pwd) < 5) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '密码长度不能小于6位',
                        'data' => null
                    );
                    return $return;
                } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $new_pwd)) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                        'data' => null
                    );
                    return $return;
                } else {
                    $check = Port::checkPhnoe($phone, $phone_code);

                    if ($check['errorNum']) {
                        return $check;
                    }
                    $member = UcenterMember::findOne($is_reg);
                    if ($member) {
                        $app_pwd = md5(sha1($new_pwd) . time());
                        $hash_pwd = Yii::$app->security->generatePasswordHash($new_pwd);
                        $member->password_hash = $hash_pwd;
                        $member->app_pwd = $app_pwd;
                        $res = $member->save();
                        //修改密码成功---对登陆状态做判定
                        if ($res) {
                            $key = Sessionkey::find()->where([
                                'uid' => $is_reg,
                                'status' => self::STATUS_ACTIVE
                            ])->one();
                            //如果用户是在登陆状态进行的操作-----让用户重新登陆
                            if ($key) {
                                $key->status = self::STATUS_DELETE;
                                if ($key->save()) {
                                    $return = array(
                                        'errorNum' => '0',
                                        'errorMsg' => "success",
                                        'data' => null
                                    );
                                    return $return;
                                } else {
                                    $return = array(
                                        'errorNum' => '1',
                                        'errorMsg' => "用户下线失败",
                                        'data' => null
                                    );
                                    return $return;
                                }
                            }//用户在未登录下操作
                            else {
                                $return = array(
                                    'errorNum' => '0',
                                    'errorMsg' => "success",
                                    'data' => null
                                );
                                return $return;
                            }
                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => "修改密码失败",
                                'data' => null
                            );
                            return $return;
                        }
                    }
                }

            }//未实名认证用户
            else {
                if ($new_pwd != $rep_pwd) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '两次密码不一致',
                        'data' => null
                    );
                    return $return;
                } elseif (strlen($new_pwd) < 5) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '密码长度不能小于6位',
                        'data' => null
                    );
                    return $return;
                } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $new_pwd)) {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                        'data' => null
                    );
                    return $return;
                } else {
                    $check = Port::checkPhnoe($phone, $phone_code);
                    if ($check['errorNum']) {
                        return $check;
                    }
                    $member = UcenterMember::findOne($is_reg);
                    if ($member) {
                        $app_pwd = md5(sha1($new_pwd) . time());
                        $hash_pwd = Yii::$app->security->generatePasswordHash($new_pwd);
                        $member->password_hash = $hash_pwd;
                        $member->app_pwd = $app_pwd;
                        $res = $member->save();
                        //修改密码成功---对登陆状态做判定
                        if ($res) {
                            $key = Sessionkey::find()->where([
                                'uid' => $is_reg,
                                'status' => self::STATUS_ACTIVE
                            ])->one();
                            //如果用户是在登陆状态进行的操作-----让用户重新登陆
                            if ($key) {
                                $key->status = self::STATUS_DELETE;
                                if ($key->save()) {
                                    $return = array(
                                        'errorNum' => '0',
                                        'errorMsg' => "success",
                                        'data' => null
                                    );
                                    return $return;
                                } else {
                                    $return = array(
                                        'errorNum' => '1',
                                        'errorMsg' => "用户下线失败",
                                        'data' => null
                                    );
                                    return $return;
                                }
                            }//用户在未登录下操作
                            else {
                                $return = array(
                                    'errorNum' => '0',
                                    'errorMsg' => "success",
                                    'data' => null
                                );
                                return $return;
                            }
                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => "修改密码失败",
                                'data' => null
                            );
                            return $return;
                        }
                    }
                }
            }

        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => "用户不存在",
                'data' => null
            );
            return $return;
        }

    }

    /**
     * 用户签到
     * @param $uid
     * @param $from
     * @return array
     */
    public static function signIn($uid, $from)
    {
        //正式上线再定
        $info = Info::findOne([
            'member_id' => $uid
        ]);
        if (!$info) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => "用户不存在",
                'data' => null
            );
            return $return;
        }
        //获取在投资金
        $invest = $info->invest;
        //
        if ($invest < 1000) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => "在投金额大于1000的用户才能签到",
                'data' => null
            );
            return $return;
        }
        //获取最新签到记录
        $get_sign = SignIn::find()->where(['uid' => $uid])->orderBy('sign_in_time DESC')->one();
        if ($get_sign) {
            $sign_time = $get_sign->sign_in_time;
            //今日零时时间
            $zero_time = strtotime(date("Y-m-d"));
            //明日零时时间
//            $tom_zero_time = $zero_time + 86400;
            if ($sign_time < $zero_time) {
                //最后一次签到时间小于当日零时--可以签到
                $log = new SignIn();
                $log->uid = $uid;
                $log->sign_in_time = time();
                $log->sign_in_money = 0;
                $log->sign_in_ip = Yii::$app->request->userIp;
                $log->sign_in_from = $from;
                $log->status = SignIn::STATUS_ACTIVE;
                if ($log->save()) {
                    $result = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => null
                    );
                    return $result;
                } else {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '签到失败',
                        'data' => null
                    );
                    return $result;
                }
            } else {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '已经签到',
                    'data' => null
                );
                return $result;
            }
        } else {
            //直接签到
            $log = new SignIn();
            $log->uid = $uid;
            $log->sign_in_time = time();
            $log->sign_in_money = 0;
            $log->sign_in_ip = Yii::$app->request->userIp;
            $log->sign_in_from = $from;
            $log->status = SignIn::STATUS_ACTIVE;
            if ($log->save()) {
                $result = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $result;
            } else {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '签到失败',
                    'data' => null
                );
                return $result;
            }
        }
    }

    /**
     * 判定用户今日是否可以签到
     * @param $uid
     * @return array
     */
    public static function is_sign_today($uid)
    {
        $get_sign = SignIn::find()->where(['uid' => $uid])->orderBy('sign_in_time DESC')->one();
        if ($get_sign) {
            //有签到记录
            $zero_time = strtotime(date("Y-m-d"));
            $sign_time = $get_sign->sign_in_time;
            //最后签到时间小于今日零时时间---可以签到
            if ($sign_time < $zero_time) {
                $result = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $result;
            } else {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '已经签到',
                    'data' => null
                );
                return $result;
            }
        } else {
            //不存在签到记录--可以签到
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null
            );
            return $result;
        }
    }

    /**
     * 获取用户签到记录---成功获取到奖励的记录
     * @param $uid 用户id
     * @param int $page_no 页号
     * @param int $page_size 每页条数
     * @return array
     */
    public static function get_user_sign_in($uid, $page_no = 1, $page_size = 2)
    {
        //获取用户签到记录
        $num = ($page_no - 1) * $page_size;
        $get_sign = SignIn::find()->select(['sign_in_time', 'sign_in_money'])->where(['uid' => $uid, 'status' => SignIn::STATUS_FINISH])->orderBy('id desc')->limit($page_size)->offset($num)->asArray()->all();
        $get_sign_money = SignIn::find()->where(['uid' => $uid, 'status' => SignIn::STATUS_FINISH])->sum('sign_in_money');
        $get_sign_count = SignIn::find()->where(['uid' => $uid, 'status' => SignIn::STATUS_FINISH])->count();
        $user = array(
            'total_money' => $get_sign_money,
            'total_day' => $get_sign_count,
            'list' => $get_sign
        );
        if ($get_sign) {
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $user
            );
            return $result;
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '用户尚未签到',
                'data' => null
            );
            return $result;
        }
    }

    /**
     * 获取用户昨日情况
     * @param $uid
     * @return array
     */
    public static function get_yesterday_user($uid)
    {
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //昨日零时时间
        $yes_zero_time = $zero_time - 86400;
        $get_sign_money = SignIn::find()->where(['between', 'sign_in_time', $yes_zero_time, $zero_time])->andWhere(['uid' => $uid, 'status' => SignIn::STATUS_FINISH])->one();
        if ($get_sign_money) {
            $suppose = ($get_sign_money->sign_in_money) * 365 / 0.08;
            $data = array(
                'money' => $get_sign_money->sign_in_money,
                'suppose' => $suppose
            );
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $result;
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '用户昨日未签到',
                'data' => null
            );
            return $result;
        }
    }

    /**
     * 获取昨日签到情况
     * @return array
     */
    public static function get_yesterday_sign_in()
    {
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //昨日零时时间
        $yes_zero_time = $zero_time - 86400;
        //昨日签到人数
        $get_sign_count = SignIn::find()->where(['between', 'sign_in_time', $yes_zero_time, $zero_time])->andWhere(['status' => SignIn::STATUS_FINISH])->count();
        //昨日红包总额
        $get_sign_money = SignIn::find()->where(['between', 'sign_in_time', $yes_zero_time, $zero_time])->andWhere(['status' => SignIn::STATUS_FINISH])->sum('sign_in_money');
        $data = array(
            'count' => $get_sign_count,
            'money' => $get_sign_money
        );
        $result = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $data
        );
        return $result;
    }

    /**
     * 获取今日签到情况
     * @return array
     */
    public static function get_today_sign_in()
    {
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        //今日签到人数
        $get_sign_count = SignIn::find()->where(['between', 'sign_in_time', $zero_time, $tom_zero_time])->count();
        $data = array(
            'count' => $get_sign_count
        );
        $result = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $data
        );
        return $result;
    }

    /**
     * 获取网站签到情况
     * @return array
     */
    public static function get_sign_in()
    {
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //昨日零时时间
        $yes_zero_time = $zero_time - 86400;
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        //昨日签到人数
        $get_sign_count = SignIn::find()->where(['between', 'sign_in_time', $yes_zero_time, $zero_time])->andWhere(['status' => SignIn::STATUS_FINISH])->count();
        //昨日红包总额
        $get_sign_money = SignIn::find()->where(['between', 'sign_in_time', $yes_zero_time, $zero_time])->andWhere(['status' => SignIn::STATUS_FINISH])->sum('sign_in_money');
        //今日签到人数
        $get_today_sign_count = SignIn::find()->where(['between', 'sign_in_time', $zero_time, $tom_zero_time])->count();
        $data = array(
            'yes_count' => $get_sign_count,
            'yes_money' => $get_sign_money,
            'today_count' => $get_today_sign_count
        );
        $result = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $data
        );
        return $result;
    }

    /**
     * 生成邀请码
     * @param $num
     * @return bool
     */
    public static function set_invitation($num = '5')
    {
        $count = 0;
        for ($i = 0; $i < $num; $i++) {

            // 判断邀请码是否已存在
            $code = self::random();
            do {
                $res = InvitationCode::findOne([
                    'code' => $code,
                ]);
                if ($res) {
                    $code = self::random();
                    continue;
                } else {
                    break;
                }
            } while (true);

            $invia = new InvitationCode();
            $invia->code = self::random();
            $invia->status = InvitationCode::STATUS_ACTIVE;
            $flag = $invia->save();
            if (!$flag) {
                break;
            }
            $count++;
        }
        if ($count == $num) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证邀请码
     * @param $code
     * @return bool|int
     */
    public static function verify_code($code)
    {
        $code = InvitationCode::findOne([
            'status' => InvitationCode::STATUS_ACTIVE,
            'code' => $code
        ]);
        if ($code) {
            return $code->id;
        }
        return false;
    }

    /**
     * 使用邀请码
     * @param $id
     * @param $uid
     * @return bool|string
     */
    public static function use_code($id, $uid)
    {
        $log = InvitationCode::findOne($id);
        if ($log) {
            $log->use_id = $uid;
            $log->status = InvitationCode::STATUS_USED;
            if ($log->save()) {
                return true;
            } else {
                return 'save error';
            }
        } else {
            return 'find error';
        }
    }

    /**
     * 获取用户今日充值次数
     * @param $uid
     * @return bool|int|string
     */
    public static function get_deposit_num($uid)
    {
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        $log = \common\models\base\asset\Log::find()->where([
            'member_id' => $uid,
            'status' => \common\models\base\asset\Log::STATUS_RECHAR_SUC
        ])->andWhere(['between', 'create_at', $zero_time, $tom_zero_time])->count();
        if ($log) {
            return $log;
        } else {
            return false;
        }
    }

    /**
     * 获取用户今日投资次数
     * @param $uid
     * @return bool|int|string
     */
    public static function get_invest_num($uid)
    {
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        $log = \common\models\base\asset\Log::find()->where([
            'member_id' => $uid,
            'status' => \common\models\base\asset\Log::STATUS_INVEST_SUC
        ])->andWhere(['between', 'create_at', $zero_time, $tom_zero_time])->count();
        if ($log) {
            return $log;
        } else {
            return false;
        }
    }

    /**
     * 获取用户今日赎回次数
     * @param $uid
     * @return bool|int|string
     */
    public static function get_withdraw_num($uid)
    {
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        $log = \common\models\base\asset\Log::find()->where([
            'member_id' => $uid,
            'status' => \common\models\base\asset\Log::STATUS_REDEM_SUC
        ])->andWhere(['between', 'create_at', $zero_time, $tom_zero_time])->count();
        if ($log) {
            return $log;
        } else {
            return false;
        }
    }

    /**
     * 获取用户今日提现次数
     * @param $uid
     * @return bool|int|string
     */
    public static function get_ransom_num($uid)
    {
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        $log = \common\models\base\asset\Log::find()->where([
            'member_id' => $uid,
            'status' => \common\models\base\asset\Log::STATUS_WITHDRAW_SUC
        ])->andWhere(['between', 'create_at', $zero_time, $tom_zero_time])->count();
        if ($log) {
            return $log;
        } else {
            return false;
        }
    }

    /**
     * 获取用户注册手机号
     * @param $uid
     * @return bool|mixed
     */
    public static function getPhone($uid)
    {
        $user = UcenterMember::find()->where([
            'id' => $uid,
            'lock' => UcenterMember::TYPE_UNLOCK
        ])->asArray()->one();
        if ($user) {
            return $user['phone'];
        } else {
            return false;
        }

    }

    /**
     * 给予体验金
     * @param $title
     * @param $uid
     * @return bool
     */
    public static function give_experience_gold($tid, $uid)
    {
        //获取体验金规则
        $rule = Rule::find()->where(['id' => $tid, 'status' => Rule::STATUS_ACTIVE])->one();
        if ($rule !== null) {
            $rid = $rule->id;
            //规则金额
            $r_money = $rule->money;
            //规则时间
            $rule_time = $rule->time;
            //规则标题
            $title = $rule->title;
            //加入体验金记录表
            $model_gold = new Gold();;
            $model_gold->rid = $rid;
            $model_gold->money = $r_money;
            $model_gold->uid = $uid;
            $model_gold->title = $title;
            $model_gold->end_at = time() + $rule_time * 24 * 3600;
            $model_gold->save();
        }
    }


    /**
     * 活动期间注册行为
     * @param $phone
     * @param $pwd
     * @param $confirm_pwd
     * @param $phone_code
     * @param $invite_code
     * @param $actibity_source
     * @return array
     * @throws \yii\db\Exception
     */
    public static function activity_register($phone, $pwd, $phone_code, $invite_code, $actibity_source = '')
    {
        //判定手机号是否注册
        $flag = self::phoneIsRegister($phone);
        //活动期间限定注册人数
        date_default_timezone_set('PRC');
        //活动开始时间
        $begin_time = strtotime('2015-9-24');
        $count = UcenterMember::find()->where(['>', 'created_at', $begin_time])->count();
        $limit_count = 500;

        if ($flag) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '此手机号已经注册，请直接下载APP并登录',
                'data' => null
            );
            return $return;
        } elseif ($count > $limit_count) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '参与活动人数达到限定人数',
                'data' => null
            );
            return $return;
        } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $pwd)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                'data' => null
            );
            return $return;
        }
        //邀请者id
        $invitation_id = $invite_code;

        //验证手机验证码
        $check = Port::checkPhnoe($phone, $phone_code);

        //获取邀请者信息
        $info = UcenterMember::find()->where(['id' => $invitation_id])->asArray()->one();
        $invite_phone = '';
        if (!$info) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取邀请者手机号
        $invite_phone = $info['phone'];
        //用户领取红包
        $red = self::update_red_packet($invitation_id, $invite_phone, $phone, $actibity_source);
        if ($red['errorNum']) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $red['errorMsg'],
                'data' => null
            );
            return $return;
        }

        //生成app的密码
        $app_pwd = md5(sha1($pwd) . time());
        // 根据ip获取地区
        $area = self::get_area(Yii::$app->request->userIp);
        $area = $area ? $area : '地球';
        if (!$check['errorNum']) {
            //事物回滚
            $transaction = \Yii::$app->db->beginTransaction();
            try {

                $user = new UcenterMember();
                $user->username = $phone;
                $user->phone = $phone;
                $user->setPassword(trim($pwd));
                $user->create_ip = Yii::$app->request->userIp;
                $user->create_area = $area;
                $user->invitation_code = self::random();
                $user->invitation_id = (int)$invitation_id;
                $user->status = UcenterMember::STATUS_ACTIVE;
                $user->lock = UcenterMember::TYPE_UNLOCK;
                //app密码设定
                $user->app_pwd = $app_pwd;
                $user->generateAuthKey();
                if ($user->save()) {
                    //初始化用户账户
                    $asset_info = new Info();
                    $asset_info->member_id = $user['id'];
                    $asset_info->balance = 0;
                    $asset_info->freeze = 0;
                    if (!$asset_info->save()) {
                        //未初始化成功
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '账户初始化失败',
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '注册失败',
                        'data' => null
                    );
                    return $return;
                }
                $transaction->commit();

                //事务成功提交后返回数据
                $data = array(
                    'balance' => 0,
                    'phone' => $phone,
                    'money' => $red['data']['money']
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;

            } catch (\Exception $e) {
                $transaction->rollBack();
                $remark = $e->getMessage();
                $result = array('errorNum' => '1', 'errorMsg' => $remark, 'data' => null);
                return $result;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $check['errorMsg'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 获取活动链接中邀请者身份
     * 判定邀请次数
     * @param $invite_code
     * @return array
     */
    public static function get_invite_info($invite_code)
    {
        if ($invite_code == '0') {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '活动链接来源不合法',
                'data' => null
            );
            return $return;
        } else {
            //解析链接中的手机号
            $invite_phone = \frontend\actions\App\AloneMethod::decrypt($invite_code);
            //验证邀请者身份
            $invitation_id = self::phoneIsRegister($invite_phone);
            if (!$invitation_id) {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '邀请者不存在',
                    'data' => null
                );
                return $return;
            }
            //判定邀请者资格--一定要有投资行为--防止链接伪造
            $invite_code = \common\models\base\asset\Log::find()->where(['member_id' => $invitation_id, 'action' => 'Invest/invest', 'status' => \common\models\base\asset\Log::STATUS_INVEST_SUC])->count();
            if (!$invite_code) {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '邀请者不合法',
                    'data' => null
                );
                return $return;
            }
            //可以邀请
            $invite_count = ActivityLog::find()->where(['invite_id' => $invitation_id, 'invite_phone' => $invite_phone, 'status' => ActivityLog::STATUS_ACTIVITY])->count();
            //存在邀请记录
            $invite_test = ActivityLog::find()->where(['invite_id' => $invitation_id, 'invite_phone' => $invite_phone, 'status' => ActivityLog::STATUS_SUCCESS])->count();
            if ($invite_count == 0 && $invite_test) {
                $data = array(
                    'invite_phone' => $invite_phone,
                    'invitation_id' => $invitation_id
                );
                $return = array(
                    'errorNum' => '2',
                    'errorMsg' => '红包已经被抢完',
                    'data' => $data
                );
                return $return;
            } else {
                $data = array(
                    'invite_phone' => $invite_phone,
                    'invitation_id' => $invitation_id
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }
        }
    }

    /**
     * 活动记录
     * @param $phone
     * @param $invitation_id
     * @param $invite_phone
     * @param $experience_money
     * @param $red_packet
     * @param $actibity_source
     */
    private static function active_log($phone, $invitation_id, $invite_phone, $experience_money, $red_packet, $actibity_source)
    {
        $ac_log = new ActivityLog();
        $ac_log->phone = $phone;
        $ac_log->invite_id = $invitation_id;
        $ac_log->invite_phone = $invite_phone;
        $ac_log->experience_money = $experience_money;
        $ac_log->red_packet = $red_packet;
        $ac_log->actibity_source = $actibity_source;
        $ac_log->status = ActivityLog::STATUS_SUCCESS;
        $ac_log->save();
    }

    /**
     * 用户分享生成红包奖励表
     * @param $invite_phone 分享者手机号
     * @param $sum_count 生成红包的数量
     * @param $actibity_source 红包对应的活动
     * @return array
     */
    public static function create_read_packet($invite_phone, $sum_count, $actibity_source)
    {

        //获取用户id
        $invite_id = self::phoneIsRegister($invite_phone);
        if (!$invite_id) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '邀请用户不存在',
                'data' => null
            );
            return $return;
        }
        //判定是否有必要进行生成 ---已经生成
        //$count = ActivityLog::find()->where(['status' => ActivityLog::STATUS_ACTIVITY,'invite_phone' => $invite_phone])->andWhere(['>','end_at',time()])->count();
        // $count = ActivityLog::find()->where(['invite_phone' => $invite_phone])->one();
        //计算当前活动产生的红包数目
        $flag = ActivityLog::find()->where(['invite_phone' => $invite_phone, 'actibity_source' => $actibity_source])->count();

        if ($flag == 0) {
            // 设定活动时间--以天计算
            $continue_time = 100;
            //处理分配金额--设置奖励规则
//            $prize_arr = array(
//                '0' => array('id'=>1,'prize'=>'5','v'=>1),
//                '1' => array('id'=>2,'prize'=>'4.5','v'=>5),
//                '2' => array('id'=>3,'prize'=>'4','v'=>10),
//                '3' => array('id'=>4,'prize'=>'3.5','v'=>20),
//                '4' => array('id'=>5,'prize'=>'3','v'=>30),
//                '5' => array('id'=>6,'prize'=>'2.5','v'=>40),
//                '6' => array('id'=>7,'prize'=>'2','v'=>50),
//                '7' => array('id'=>8,'prize'=>'1.5','v'=>60),
//                '8' => array('id'=>9,'prize'=>'1','v'=>70),
//                '9' => array('id'=>10,'prize'=>'0.5','v'=>100),
//            );
            //活动设置奖项---
            //读取奖项设定
            $prize = array();
            $holdactivity = HoldActivity::find()->where(['id' => 7])->asArray()->one();
            $radearray = explode('/', $holdactivity['red_money_rang']);
            if (count($radearray)) {
                foreach ($radearray as $key => $value) {
                    $v1 = explode(',', $value);
                    if (count($v1)) {
                        $v2 = explode('-', $v1['0']);
                        $money = round(rand($v2['0'] * 1000, $v2['1'] * 1000) / 10) / 100;
                        $prize[$key] = array('id' => $key + 1, 'prize' => $money, 'v' => $v1['1']);

                    }
                }
            }

            //判定红包是否是双向红包
            $red_bothway = $holdactivity['red_bothway'];

            ///////////////////////////////////////
            foreach ($prize as $key => $val) {
                $arr[$val['id']] = $val['v'];
            }
            //数据处理记录
            $sum_count = $sum_count ? $sum_count : 1;
            for ($i = 1; $i <= $sum_count; $i++) {
                //概率取出奖励红包
                $rid = self::get_rand($arr);
                $red_packet = $prize[$rid - 1]['prize'];
                //记录数据
                $log = new ActivityLog();

                $log->invite_id = $invite_id;

                $log->invite_phone = $invite_phone;

                $log->red_packet = $red_packet;

                $log->status = ActivityLog::STATUS_ACTIVITY;

                $log->end_at = time() + $continue_time * 24 * 3600;

                $log->type = (int)$red_bothway;
                $log->actibity_source = $actibity_source;
                $log->inviter_draw = ActivityLog::STATUS_INVITER_DRAW_SUCC;
                $log->invitee_draw = ActivityLog::STATUS_INVITEE_DRAW_SUCC;
                $log->save();
            }
        }
    }

    /**
     * 更新红包数据--用户领取红包---活动界面推荐进行的领取
     * @param $invitation_id
     * @param $invite_phone
     * @param $phone
     * @param $actibity_source
     * @return array
     */
    public static function update_red_packet($invitation_id, $invite_phone, $phone, $actibity_source)
    {
        $flag1 = ActivityLog::find()->where(['invite_id' => $invitation_id, 'invite_phone' => $invite_phone, 'status' => ActivityLog::STATUS_SUCCESS, 'actibity_source' => $actibity_source, 'phone' => $phone])->one();
        if ($flag1) {
            $data = array('phone' => $phone, 'money' => $flag1->red_packet);
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;
        }

        $flag = ActivityLog::find()->where(['invite_id' => $invitation_id, 'invite_phone' => $invite_phone, 'status' => ActivityLog::STATUS_ACTIVITY, 'actibity_source' => $actibity_source])->andWhere(['>', 'end_at', time()])->orderBy('id asc')->one();
        if ($flag) {
            $money = $flag->red_packet;
            $flag->phone = $phone;
//            $flag->actibity_source = $actibity_source;
            $flag->status = ActivityLog::STATUS_SUCCESS;
            if ($flag->save()) {
                $data = array('phone' => $phone, 'money' => $money);
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            } else {
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '发放红包失败',
                    'data' => null
                );
                return $return;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '红包已经被抢光',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 获取红包领取情况--红包列表--活动页面进行推荐用户的显示
     * @param $invite_phone
     * @return array
     */
    public static function get_red_packet($invite_phone, $actibity_source = "")
    {
        $list = ActivityLog::find()->select(['phone', 'red_packet', 'update_at', 'type'])->where(['invite_phone' => $invite_phone, 'status' => ActivityLog::STATUS_SUCCESS, 'actibity_source' => $actibity_source])->orderBy('id asc')->asArray()->all();
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]['update_at'] = date('Y-m-d H:m:s', $value['update_at']);
                $list[$key]['phone'] = substr($value['phone'], 0, 3) . '****' . substr($value['phone'], -4);
            }
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $list
            );
            return $return;
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '暂无记录',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 经典概率计算
     * @param $proArr
     * @return int|string
     */
    public static function get_rand($proArr)
    {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    /**
     * 获取用户当前红包金额
     * @param $uid
     * @return array
     */
    public static function get_user_red_packet($uid, $update = 0)
    {
        $info = UcenterMember::find()->where(['id' => $uid])->asArray()->one();
        if ($info == null) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取用户手机号
        $phone = $info['phone'];
        //设定红包读取时间
        $update_at = 0;
        if ($update == 0) {
            //获取当前时间数据记录里的最后一条的时间
            $red_packet_last = ActivityLog::find()->where(['invite_id' => $uid, 'status' => ActivityLog::STATUS_SUCCESS])->orWhere(['phone' => $phone])->orderBy('update_at desc')->asArray()->one();
            if ($red_packet_last) {
                $update_at = $red_packet_last['update_at'];
            }
        } else {
            $update_at = $update;
        }
        //获取因为邀请了其他人而获得的奖励--对邀请者的奖励--双向红包--邀请人-还未领取赎回的
        $red_packet_invitee = ActivityLog::find()->where(['invite_id' => $uid, 'status' => ActivityLog::STATUS_SUCCESS, 'inviter_draw' => ActivityLog::STATUS_INVITER_DRAW_SUCC, 'type' => ActivityLog::RED_BOTHWAY_YES])->andWhere(['<=', 'update_at', $update_at])->sum('red_packet');
        //获取用户被邀请了而获取的奖励--对被邀请者的奖励---双向红包--被邀请人--还未领取赎回的
        $red_packet_inviter = ActivityLog::find()->where(['phone' => $phone, 'status' => ActivityLog::STATUS_SUCCESS, 'invitee_draw' => ActivityLog::STATUS_INVITEE_DRAW_SUCC, 'type' => ActivityLog::RED_BOTHWAY_YES])->andWhere(['<=', 'update_at', $update_at])->sum('red_packet');
        //获取单向红包里---单独奖励邀请者的--推荐人--还未进行赎回领取操作的
        $red_packet_olny_inviter = ActivityLog::find()->where(['invite_id' => $uid, 'status' => ActivityLog::STATUS_SUCCESS, 'inviter_draw' => ActivityLog::STATUS_INVITER_DRAW_SUCC, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER])->andWhere(['<=', 'update_at', $update_at])->sum('red_packet');
        //获取单向红包---单独奖励给被邀请者的--被推荐人--还未进行赎回领取操作的
        $red_packet_olny_invitee = ActivityLog::find()->where(['phone' => $phone, 'status' => ActivityLog::STATUS_SUCCESS, 'invitee_draw' => ActivityLog::STATUS_INVITEE_DRAW_SUCC, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITEE])->andWhere(['<=', 'update_at', $update_at])->sum('red_packet');
        //计算总和
        $red_packet_sum = $red_packet_invitee + $red_packet_inviter + $red_packet_olny_inviter + $red_packet_olny_invitee;
        $data = array('update_at' => $update_at, 'red_sum' => $red_packet_sum);
        $return = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $data
        );
        return $return;
    }

    /**
     * 用户进行赎回红包操作
     * @param $uid
     * @param $update_at
     * @return array|\common\models\base\activity\ActivityLog[]
     */
    public static function draw_red_packet($uid, $update_at)
    {
        //获取被推荐的情况下的红包
        $info = UcenterMember::find()->where(['id' => $uid])->asArray()->one();
        $phone = '';
        if (!$info) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取手机号
        $phone = $info['phone'];
        //开启事物处理
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //判定此用户是不是被推荐过---被推荐过&&没有领取奖励红包--理论上这个数据最多一条---TODO--加上红包类型
            $invitee_draw = ActivityLog::find()->where(['phone' => $phone, 'invitee_draw' => ActivityLog::STATUS_INVITEE_DRAW_SUCC, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_YES])->andWhere(['<=', 'update_at', $update_at])->one();
            if ($invitee_draw) {
                //存在被邀请记录--并且没有领取红包
                $invitee_draw->invitee_draw = ActivityLog::STATUS_INVITEE_DRAW_ERR;
                if ($invitee_draw->save() == false) {
                    throw new ErrorException('用户获取红包失败', 900);
                }
            }
            //获取用户邀请的用户记录--邀请者进行红包领取--双向红包列表
            $inviter_draw = ActivityLog::find()->select(['id'])->where(['invite_id' => $uid, 'invite_phone' => $phone, 'inviter_draw' => ActivityLog::STATUS_INVITER_DRAW_SUCC, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_YES])->andWhere(['<=', 'update_at', $update_at])->asArray()->all();
            if (!empty($inviter_draw)) {
                //存在邀请记录
                foreach ($inviter_draw as $key => $value) {
                    $flag = ActivityLog::updateAll(['inviter_draw' => ActivityLog::STATUS_INVITER_DRAW_ERR, 'type' => ActivityLog::RED_BOTHWAY_YES], ['id' => $value['id']]);
                    //状态没有改变
                    if ($flag == false) {
                        throw new ErrorException('用户获取红包失败', 900);
                    }
                }
            }
            //单向红包领取---单独奖励推荐者--推荐者进行红包领取
            $inviter_draw_one_way = ActivityLog::find()->select(['id'])->where(['invite_id' => $uid, 'inviter_draw' => ActivityLog::STATUS_INVITER_DRAW_SUCC, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER])->andWhere(['<=', 'update_at', $update_at])->asArray()->all();
            if ($inviter_draw_one_way != null) {
                //存在单向红包奖励
                foreach ($inviter_draw_one_way as $value1) {
                    $flag = ActivityLog::updateAll(['inviter_draw' => ActivityLog::STATUS_INVITER_DRAW_ERR], ['id' => $value1['id']]);
                    //状态没有改变
                    if ($flag == false) {
                        throw new ErrorException('用户获取红包失败', 900);
                    }
                }
            }
            //单向红包领取---单独奖励被推荐者--被推荐者进行红包领取
            $invitee_draw_one_way = ActivityLog::find()->select(['id'])->where(['uid' => $uid, 'invitee_draw' => ActivityLog::STATUS_INVITEE_DRAW_SUCC, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITEE])->andWhere(['<=', 'update_at', $update_at])->asArray()->all();
            if ($invitee_draw_one_way != null) {
                //存在单向红包奖励
                foreach ($invitee_draw_one_way as $value1) {
                    $flag = ActivityLog::updateAll(['invitee_draw' => ActivityLog::STATUS_INVITEE_DRAW_ERR], ['id' => $value1['id']]);
                    //状态没有改变
                    if ($flag == false) {
                        throw new ErrorException('用户获取红包失败', 900);
                    }
                }
            }
           $transaction->commit();
            //事务成功提交后返回数据
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null
            );
            return $return;
        } catch (\Exception $e) {
            //事物回滚
            $transaction->rollBack();
            //接收错误信息
            $remark = $e->getMessage();
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $remark,
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 获取用户活动红包列表---双向红包列表记录
     * @param $uid
     * @param int $page_no
     * @param int $page_size
     * @return array|\common\models\base\activity\ActivityLog[]
     */
    public static function get_rad_list($uid, $page_no = 1, $page_size = 20)
    {
        //获取用户注册手机号
        $info = UcenterMember::find()->where(['id' => $uid])->asArray()->one();
        $phone = '';
        if ($info !== null) {
            $phone = $info['phone'];
        }
        $num = ($page_no - 1) * $page_size;
        //获取是分享得到的红包
        $list = ActivityLog::find()->select(['phone', 'invite_phone', 'red_packet', 'actibity_source', 'update_at', 'inviter_draw', 'invitee_draw'])->where(['invite_id' => $uid, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER])->orWhere(['phone' => $phone, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_YES])->orWhere(['invite_phone' => $phone, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_YES])->orderBy('update_at desc')->limit($page_size)->offset($num)->asArray()->all();
        $red_packet_count = ActivityLog::find()->where(['invite_id' => $uid, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER])->orWhere(['phone' => $phone, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_YES])->orWhere(['invite_phone' => $phone, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_YES])->count();
        if ($list) {
            $red_packet_sum = ActivityLog::find()->where(['invite_id' => $uid, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER])->orWhere(['phone' => $phone, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_YES])->orWhere(['invite_phone' => $phone, 'status' => ActivityLog::STATUS_SUCCESS, 'type' => ActivityLog::RED_BOTHWAY_YES])->sum('red_packet');
            $red_packet_sum = $red_packet_sum ? $red_packet_sum : 0;
            foreach ($list as $key => $value) {
                if ($value['invite_phone'] == $phone) {
                    //邀请其他用户获得的奖励
                    $list[$key]['type'] = 1;
                    $list[$key]['phone'] = $value['phone'];
                    unset($list[$key]['invite_phone']);
                } else {
                    //被邀请获得的奖励
                    $list[$key]['type'] = 2;
                    $list[$key]['phone'] = $value['invite_phone'];
                    unset($list[$key]['invite_phone']);
                }
            }
            $data = array('sum' => $red_packet_sum, 'count' => $red_packet_count, 'list' => $list);
            $return = array(
                'errorNum' => 0,
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;
        } elseif (!$list && $red_packet_count) {
            $return = array(
                'errorNum' => 1,
                'errorMsg' => '没有更多的记录',
                'data' => null
            );
            return $return;
        } else {
            $return = array(
                'errorNum' => 1,
                'errorMsg' => '暂无记录',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 根据ip获取地区
     * @param $ip
     * @return bool|string
     */
    public static function get_area($ip)
    {
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if (empty($res)) {
            return false;
        }
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if (!isset($jsonMatches[0])) {
            return false;
        }
        $json = json_decode($jsonMatches[0], true);
        if (isset($json['ret']) && $json['ret'] == 1) {
            $json['ip'] = $ip;
            unset($json['ret']);
        } else {
            return false;
        }
        $area = $json['country'] . '-' . $json['province'] . '-' . $json['city'];
        return $area;
    }

    /**
     * 生成随机不重复八位邀请码
     * @return string
     */
    private static function random()
    {
        $random = substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return $random;
    }

    /**
     * 生成唯一字符串作为用户标识
     * @param $uid
     * @return string
     */
    private static function verification($uid = '')
    {
        $length = 6;
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return md5(microtime() . $str . $uid) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     *推广大师注册方法
     * @param $phone 手机号
     * @param $pwd  密码
     * @param $phone_code 手机验证码
     * @param $from 用户来源
     * @param string $invite_code 邀请码
     * @return array
     */
    public static function  the_master_register($phone, $pwd, $phone_code, $invitation_id,$userIp = '')
    {
        $from = 1015;
        //开启推广大师
        $recommend_the_master = 1;
        //判定手机号是否注册
        $flag = self::phoneIsRegister($phone);
        if ($flag) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '此手机号已注册，请直接登录',
                'data' => null
            );
            return $return;
        } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $pwd)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                'data' => null
            );
            return $return;
        }
        //邀请者id
        $user = UcenterMember::findOne([
                'id' => $invitation_id,
        ]);
        //不存在邀请者
        if ($user == null) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '邀请者不存在',
                'data' => null
            );
            return $return;
        }
        //手机短息验证码验证
        $check = Port::checkPhnoe($phone, $phone_code);
        $session_key = "";
        //生成app的密码
        $app_pwd = md5(sha1($pwd) . time());
        // 根据ip获取地区
        $area = self::get_area($userIp);
        $area = $area ? $area : '地球';
        if (!$check['errorNum']) {
            //事物回滚
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $user = new UcenterMember();
                $user->username = $phone;
                $user->phone = $phone;
                $user->setPassword(trim($pwd));
                $user->create_ip = $userIp;
                $user->create_area = $area;
                $user->create_channel = (int)$from;
                $user->invitation_code = self::random();
                $user->invitation_id = (int)$invitation_id;
                $user->status = UcenterMember::STATUS_ACTIVE;
                $user->lock = UcenterMember::TYPE_UNLOCK;
                //app密码设定
                $user->app_pwd = $app_pwd;
                ///
                $user->generateAuthKey();
                if ($user->save()) {
                    //生成邀请码
                    $new_uid = $user->id;
                    $invitation_code = 'v' . $new_uid * 99;
                    $user->invitation_code = $invitation_code;
                    $user->save();
                    //初始换用户账户
                    $asset_info = new Info();
                    $asset_info->member_id = $user['id'];
                    $asset_info->balance = 0;
                    $asset_info->freeze = 0;
                    if ($asset_info->save()) {
                        $uid = $user['id'];
                        if ($recommend_the_master) {
                            //推荐大师活动开启了
                            //用户注册--增加数据表
                            $produce = \frontend\actions\AloneMethod::produce_recommend($uid);
                            if ($produce['errorNum'] == '1') {
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => '推荐大师活动错误',
                                    'data' => null
                                );
                                return $return;
                            }
                        }
                        //注册动作完成---进行登录操作
                        $log = new Log();
                        $log->member_id = $uid;
                        $log->login_ip = Yii::$app->request->userIp;
                        $log->login_time = time();
                        $log->login_area = $area;
                        $log->status = self::LOG_CONFIM;
                        $res = $log->save();
                        if ($res) {
                            $session_key = self::verification($uid) . '--' . $from;
                            $session = new Sessionkey();
                            $session->uid = $uid;
                            $session->sessionkey = $session_key;
                            $session->status = self::STATUS_ACTIVE;
                            $res = $session->save();
                            if ($res) {
                                $log->status = self::LOG_SUSSESS;
                                $log->save();
                                //新用户注册送体验金---区分推荐用户和非推荐用户
                                if ($invitation_id == 0) {
                                    //普通注册
                                    self::give_experience_gold(1, $user['id']);
                                } else {
                                    self::give_experience_gold(7, $user['id']);
                                }
                            } else {
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => '登陆失败',
                                    'data' => null
                                );
                                return $return;
                            }

                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '登陆记录失败',
                                'data' => null
                            );
                            return $return;
                        }
                    } else {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '账户初始化失败',
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '注册失败',
                        'data' => null
                    );
                    return $return;
                }
                $transaction->commit();

                //事务成功提交后返回数据
                $data = array(
                    'sessionkey' => $session_key,
                    'balance' => 0,
                    'phone' => $phone,
                    'app_pwd' => $app_pwd
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;

            } catch (\Exception $e) {
                $transaction->rollBack();
                $remark = $e->getMessage();
                $result = array('errorNum' => '1', 'errorMsg' => $remark, 'data' => null);
                return $result;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $check['errorMsg'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     *以后做推广活动进行的注册操作
     * @param $phone 手机号
     * @param $pwd  密码
     * @param $phone_code 手机验证码
     * @param $from 用户来源
     * @param string  $userIp 注册ip
     * @param string  $from  注册来源
     * @return array
     */
    public static function  the_promotion_register($phone, $pwd, $phone_code,$from)
    {
        //注册来源
        $from = $from;
        //判定手机号是否注册
        $flag = self::phoneIsRegister($phone);
        if ($flag) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '此手机号已注册，请直接登录',
                'data' => null
            );
            return $return;
        } elseif (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9a-zA-Z]{6,16}$/', $pwd)) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '密码应该是数字、字母组成的6到16位字符',
                'data' => null
            );
            return $return;
        }
        //手机短息验证码验证
        $check = Port::checkPhnoe($phone, $phone_code);
        $session_key = "";
        //生成app的密码
        $app_pwd = md5(sha1($pwd) . time());
        // 根据ip获取地区---Yii::$app->request->userIp
        $userIp = Yii::$app->request->userIp;
        $area = self::get_area($userIp);
        $area = $area ? $area : '地球';
        if (!$check['errorNum']) {
            //事物回滚
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $user = new UcenterMember();
                $user->username = $phone;
                $user->phone = $phone;
                $user->setPassword(trim($pwd));
                $user->create_ip = $userIp;
                $user->create_area = $area;
                $user->create_channel = (int)$from;
                $user->status = UcenterMember::STATUS_ACTIVE;
                $user->lock = UcenterMember::TYPE_UNLOCK;
                //app密码设定
                $user->app_pwd = $app_pwd;
                ///
                $user->generateAuthKey();
                if ($user->save()) {
                    //生成邀请码
                    $new_uid = $user->id;
                    $invitation_code = 'v' . $new_uid * 99;
                    $user->invitation_code = $invitation_code;
                    $user->save();
                    //初始换用户账户
                    $asset_info = new Info();
                    $asset_info->member_id = $user['id'];
                    $asset_info->balance = 0;
                    $asset_info->freeze = 0;
                    if ($asset_info->save()) {
                        $uid = $user['id'];
                        //注册动作完成---进行登录操作
                        $log = new Log();
                        $log->member_id = $uid;
                        $log->login_ip = Yii::$app->request->userIp;
                        $log->login_time = time();
                        $log->login_area = $area;
                        $log->status = self::LOG_CONFIM;
                        $res = $log->save();
                        if ($res) {
                            $session_key = self::verification($uid) . '--' . $from;
                            $session = new Sessionkey();
                            $session->uid = $uid;
                            $session->sessionkey = $session_key;
                            $session->status = self::STATUS_ACTIVE;
                            $res = $session->save();
                            if ($res) {
                                $log->status = self::LOG_SUSSESS;
                                $log->save();
                                //新用户注册送体验金---6666
                                self::give_experience_gold(1, $user['id']);
                            } else {
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => '登陆失败',
                                    'data' => null
                                );
                                return $return;
                            }

                        } else {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '登陆记录失败',
                                'data' => null
                            );
                            return $return;
                        }
                    } else {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '账户初始化失败',
                            'data' => null
                        );
                        return $return;
                    }
                } else {
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '注册失败',
                        'data' => null
                    );
                    return $return;
                }
                $transaction->commit();

                //事务成功提交后返回数据
                $data = array(
                    'sessionkey' => $session_key,
                    'balance' => 0,
                    'phone' => $phone,
                    'app_pwd' => $app_pwd
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;

            } catch (\Exception $e) {
                $transaction->rollBack();
                $remark = $e->getMessage();
                $result = array('errorNum' => '1', 'errorMsg' => $remark, 'data' => null);
                return $result;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $check['errorMsg'],
                'data' => null
            );
            return $return;
        }
    }
}