<?php
namespace backend\controllers;


use common\models\base\asset\Info;
use common\models\base\asset\Log;
use frontend\actions\AloneMethod;
use frontend\actions\app\member;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use common\models\base\fund\Order;
use common\models\yeepay\Payment;
use common\models\yeepay\Withdraw;
use common\models\UcenterMember;
use framework\helpers\Utils;
use common\models\base\fund\Thirdproduct;
use common\models\sinapay\SinaDeposit;
use common\models\sinapay\SinaWithdraw;
use common\models\base\asset\TradeLog;
use yii\data\Pagination;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'ajaxdata', 'export','exportuser'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $betime = strtotime(date('Y-m-d' . ' 00:00:00', time()));
        $endtime = strtotime(date('Y-m-d' . ' 23:59:59', time()));

        $order = Order::find()
            ->andWhere(['between', 'start_at', $betime, $endtime])
            ->asArray()
            ->count();
        $payment = SinaDeposit::find()
            ->andWhere(['status' => SinaDeposit::STATUS_SUCCESS])
            ->andWhere(['between', 'create_at', $betime, $endtime])
            ->asArray()
            ->count();
        $withdraw = SinaWithdraw::find()
            ->andWhere(['status' => SinaWithdraw::STATUS_SINA_SUCCESS])
            ->andWhere(['between', 'create_at', $betime, $endtime])
            ->asArray()
            ->count();

        $product = Thirdproduct::find()
            ->andWhere(['status' => Thirdproduct::STATUS_ACTIVE])
            ->andWhere(['intent' => Thirdproduct::INTENT_CHECK])
            ->andWhere(['create_user_id' => Yii::$app->user->identity->getId()])
            ->asArray()
            ->all();
        $user = UcenterMember::find()
            ->andWhere(['between', 'created_at', $betime, $endtime])
            ->asArray()
            ->count();
        $log = TradeLog::find()->orderBy('t_date desc');

        $pages = new Pagination(['totalCount' => $log->count(), 'pageSize' => '10']);
        $log = $log->offset($pages->offset)->limit($pages->limit)->all();

        AloneMethod::trade_log();

        $cur_recharge = TradeLog::find()->sum('t_recharge');
        $cur_invest = TradeLog::find()->sum('t_invest');
        $cur_redeem = TradeLog::find()->sum('t_redeem');
        $cur_withdraw = TradeLog::find()->sum('t_withdraw');
        $cur_profit = TradeLog::find()->sum('t_profit');
        $cur_glod = TradeLog::find()->sum('t_gold');
        $cur_red = TradeLog::find()->sum('t_red');

        $ret_msg = '当前总额： 充值【'.$cur_recharge.'】 投资【'.$cur_invest.'】 赎回【'.$cur_redeem.'】 提现【'.$cur_withdraw.'】 在投【'.$cur_profit.'】 体验金收益【'.$cur_glod.'】 红包【'.$cur_red.'】';

        return $this->render('index',
            [
                'withdraw' => $withdraw,
                'payment' => $payment,
                'order' => $order,
                'user' => $user,
                'product' => $product,
                'log' => $log,
                'pages' => $pages,
                'ret_msg' => $ret_msg
            ]);
    }

    public function actionExport()
    {

        $logs = TradeLog::find()->asArray()->orderBy('t_date desc')->all();
        $v = array();
        foreach ($logs as $key => $item) {
            $v[$key]['data'] = date('Y-m-d', $item['t_date']);
            $v[$key]['t_recharge'] = $item['t_recharge'];
            $v[$key]['t_withdraw'] = $item['t_withdraw'];


            $v[$key]['bl'] = $item['t_recharge'] != 0 ?(ceil($item['t_withdraw'] / $item['t_recharge'] * 10000) / 100) . '%' : '';
            $v[$key]['t_invest'] = $item['t_invest'];
            $v[$key]['t_redeem'] = $item['t_redeem'];
            $v[$key]['bl1'] = $item['t_invest'] != 0 ? (ceil($item['t_redeem'] / $item['t_invest'] * 10000) / 100) . '%' : '';
            $v[$key]['t_profit'] = $item['t_profit'];
            $v[$key]['t_gold'] = $item['t_gold'];
            $v[$key]['t_red'] = $item['t_red'];
            $v[$key]['ztz'] = $item['t_profit'] + $item['t_gold'] + $item['t_red'];
            $v[$key]['create_at'] = date('Y-m-d', $item['create_at']);


        }
        Utils::exportExcel($v,
            [
                '时间', '充值金额', '提现金额', '比率', '投资金额', '赎回金额', '比率', '在投收益', '体验金收益', '红包收益', '总收益', '创建时间'
            ], '记录导出' . date('Y.m.d')
        );
    }
    public function actionExportuser(){
        $users = UcenterMember::find()->asArray()->all();
        $data = array();
        if($users !== null){
            foreach($users as $key => $value){
                $data[$key]['id'] = $value['id'];
                $data[$key]['phone'] = $value['phone'];
                $data[$key]['real_name'] = $value['real_name'];
                $data[$key]['created_at'] = date('Y-m-d :H-m-s',$value['created_at']);
                $data[$key]['create_area'] = $value['create_area'];
                $status = $value['status'];
                if($status == UcenterMember::STATUS_ACTIVE){
                    $data[$key]['status'] = '注册用户';
                }elseif($status == UcenterMember::STATUS_REAL){
                    $data[$key]['status'] = '实名用户';
                }elseif($status == UcenterMember::STATUS_BIND){
                    $data[$key]['status'] = '绑卡用户';
                }
                //账户信息
                $info = Info::find()->where(['member_id' => $value['id']])->one();
                //账户余额
                $data[$key]['balance'] = $info['balance'];
                //在投资金
                $data[$key]['invest'] = $info['invest'];
                //可用收益
                $data[$key]['profit'] = $info['profit'];
                //累计收益
                $data[$key]['total_revenue'] = $info['total_revenue'];
                //充值--投资--赎回--提现
                //首次充值
                $rechar_at = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_RECHAR_SUC])->orderBy('id asc')->one();
                //充值总金额
                $rechar_sum = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_RECHAR_SUC])->sum('step');
                //首次投资时间
                $invest_at = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_INVEST_SUC])->orderBy('id asc')->one();
                //投资总额
                $invest_sum = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_INVEST_SUC])->sum('step');
                //赎回总额
                $redem_sum = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_REDEM_SUC])->sum('step');
                //提现总额
                $withdraw_sum = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_WITHDRAW_SUC])->sum('step');

                //首次充值时间
                $data[$key]['rechar_at'] =  $rechar_at ? date('Y-m-d :H-m-s',$rechar_at['create_at']) : 0;
                $data[$key]['rechar_sum'] = $rechar_sum;
                //首次投资时间
                $data[$key]['invest_at'] = $invest_at ?  date('Y-m-d :H-m-s',$invest_at['create_at']) : 0;
                $data[$key]['invest_sum'] = $invest_sum;
                //赎回
                $data[$key]['redem_sum'] = $redem_sum;
                //提现
                $data[$key]['withdraw_sum'] = $withdraw_sum;
                //未领取红包奖励
                $red = member::get_user_red_packet($value['id']);
                $data[$key]['red_usable'] = $red['data']['red_sum'];
                //总的红包奖励
                $red1 = member::get_rad_list($value['id']);
                $data[$key]['red_sum'] = $red1['data']['sum'];

            }
            return  Utils::exportExcel($data,array('用户id','用户手机号','用户真实姓名','用户注册时间','用户注册地区','用户状态','用户账户余额','在投资金','可用收益','累计收益','首次充值时间','充值总金额','首次投资时间','投资总额','赎回总额','提现总额','未领取红包','红包奖励总额'), '数据分析'.date('Y-m-d-H-m-s'));
        }
    }

    public function actionAjaxdata()
    {
        $betime = strtotime(date('Y-m-d' . ' 00:00:00', time()));
        $endtime = strtotime(date('Y-m-d' . ' 23:59:59', time()));
        $now = strftime('%Y-%m-%d', time());
        list($wstart, $wend) = Utils::lastNWeek(time(), 1);
        list($mstart, $mend) = Utils::lastMonth(time());
        list($qstart, $qend) = Utils::lastQuarter(time());

        $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));

        $today = [];
        $week = [];
        $month = [];
        $quart = [];
        $payment = SinaDeposit::find()
            ->select('sum(amount) as smoney')
            ->andWhere(['status' => SinaDeposit::STATUS_SUCCESS])
            ->andWhere(['between', 'create_at', $betime, $endtime])
            ->asArray()
            ->one();

        $withdraw = SinaWithdraw::find()
            ->select('sum(money) as smoney')
            ->andWhere(['status' => SinaWithdraw::STATUS_SINA_SUCCESS])
            ->andWhere(['between', 'create_at', $betime, $endtime])
            ->asArray()
            ->one();

        $wpayment = SinaDeposit::find()
            ->select('sum(amount) as smoney')
            ->andWhere(['status' => SinaDeposit::STATUS_SUCCESS])
            ->andWhere(['between', 'create_at', strtotime($wstart), strtotime($wend)])
            ->asArray()
            ->one();

        $wwithdraw = SinaWithdraw::find()
            ->select('sum(money) as smoney')
            ->andWhere(['status' => SinaWithdraw::STATUS_SINA_SUCCESS])
            ->andWhere(['between', 'create_at', strtotime($wstart), strtotime($wend)])
            ->asArray()
            ->one();

        $mpayment = SinaDeposit::find()
            ->select('sum(amount) as smoney')
            ->andWhere(['status' => SinaDeposit::STATUS_SUCCESS])
            ->andWhere(['between', 'create_at', strtotime($mstart), strtotime($mend)])
            ->asArray()
            ->one();

        $mwithdraw = SinaWithdraw::find()
            ->select('sum(money) as smoney')
            ->andWhere(['status' => SinaWithdraw::STATUS_SINA_SUCCESS])
            ->andWhere(['between', 'create_at', strtotime($mstart), strtotime($mend)])
            ->asArray()
            ->one();
        $qpayment = SinaDeposit::find()
            ->select('sum(amount) as smoney')
            ->andWhere(['status' => SinaDeposit::STATUS_SUCCESS])
            ->andWhere(['between', 'create_at', strtotime($qstart), strtotime($qend)])
            ->asArray()
            ->one();

        $qwithdraw = SinaWithdraw::find()
            ->select('sum(money) as smoney')
            ->andWhere(['status' => SinaWithdraw::STATUS_SINA_SUCCESS])
            ->andWhere(['between', 'create_at', strtotime($qstart), strtotime($qend)])
            ->asArray()
            ->one();
        $benpayment = SinaDeposit::find()
            ->select('sum(amount) as smoney')
            ->andWhere(['status' => SinaDeposit::STATUS_SUCCESS])
            ->andWhere(['between', 'create_at', $beginThismonth, $endThismonth])
            ->asArray()
            ->one();

        $benwithdraw = SinaWithdraw::find()
            ->select('sum(money) as smoney')
            ->andWhere(['status' => SinaWithdraw::STATUS_SINA_SUCCESS])
            ->andWhere(['between', 'create_at', $beginThismonth, $endThismonth])
            ->asArray()
            ->one();
        $oldpayment = SinaDeposit::find()
            ->select('sum(amount) as smoney')
            ->andWhere(['status' => SinaDeposit::STATUS_SUCCESS])
            ->asArray()
            ->one();

        $oldwithdraw = SinaWithdraw::find()
            ->select('sum(money) as smoney')
            ->andWhere(['status' => SinaWithdraw::STATUS_SINA_SUCCESS])
            ->asArray()
            ->one();
        $today = '今日提现' . ($withdraw['smoney'] ? $withdraw['smoney'] : '0') . '充值' . ($payment['smoney'] ? $payment['smoney'] : "0");
        $week = '上周提现' . ($wwithdraw['smoney'] ? $wwithdraw['smoney'] : '0') . '充值' . ($wpayment['smoney'] ? $wpayment['smoney'] : '0');
        $benmonth = '本月提现' . ($benwithdraw ['smoney'] ? $benwithdraw ['smoney'] : '0') . '充值' . ($benpayment['smoney'] ? $benpayment['smoney'] : "0");
        $month = '上月提现' . ($mwithdraw ['smoney'] ? $mwithdraw ['smoney'] : '0') . '充值' . ($mpayment['smoney'] ? $mpayment['smoney'] : "0");
        $quart = '上个季度提现' . ($qwithdraw['smoney'] ? $qwithdraw['smoney'] : '0') . '充值' . ($qpayment['smoney'] ? $qpayment['smoney'] : '0');
        $oldtotal = '总提现' . ($oldwithdraw['smoney'] ? $oldwithdraw['smoney'] : '0') . '充值' . ($oldpayment['smoney'] ? $oldpayment['smoney'] : '0');

        echo $today . ',' . $week . ',' . $benmonth . ',' . $month . ',' . $quart . ',' . $oldtotal ;

    }

    public function actionLogin()
    {
        $this->layout = 'guest';

        if (!\App::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(\App::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        \App::$app->user->logout();

        return $this->goHome();
    }

}
