<?php
/**
 * Created by PhpStorm.
 * User: Pele
 * Date: 2015/9/21
 * Time: 15:22
 */

namespace www\controllers;

use common\models\UcenterMember;
use frontend\actions\app\member;
use Yii;
use yii\web\Controller;

class EventsController extends Controller
{
    public $layout = false;
    //815活动-注册送红包
    // http://www.licaiwang.com/Events/MoonFestival?code=asdfasdfasdfsa

    public function actionFestival20150924intro(){
//        echo 'aaa';
        return $this->renderPartial('Festival');
    }

    public function actionFestival20151001intro(){
        return $this->renderPartial('Festival-1001');
    }

    public function actionFestival20151015intro(){
        if ($this->IsMobile()) {
            return $this->renderPartial('Festival-1016M');
        }
        else{
            return $this->renderPartial('Festival-1015');
        }
    }
    public function actionFestival20151016intro(){
        return $this->renderPartial('Festival-1016-1');
    }


    /**
     * 八月十五中秋节活动
     * @return string
     */
    public function actionFestival20150924(){
        header("Content-type: text/html; charset=utf-8");
        echo "<script>alert('活动已经过期')</script>";
        echo "<script>window.location.href='//www.licaiwang.com'</script>";

        $invite_code = Yii::$app->request->get('code', '0');
        //验证邀请码的正确性
        $invite_info = member::get_invite_info($invite_code);
        if($invite_info['errorNum'] == '1'){
            //邀请链接有问题
            header("Content-type: text/html; charset=utf-8");
            echo "<script>alert('来源链接不合法')</script>";
            echo "<script>window.location.href='//www.licaiwang.com'</script>";
            return json_encode($invite_info);
        }
        $actibity_source = '中秋节活动';
        $invite_list = member::get_red_packet($invite_info['data']['invite_phone'],$actibity_source);
        $invite_phone = substr($invite_info['data']['invite_phone'],0,3).'****'.substr($invite_info['data']['invite_phone'],-4);
        if ($this->IsMobile()) {
            return $this->renderPartial('MoonFestivalM',compact("invite_list","invite_phone"));
        }
        else{
            return $this->renderPartial('MoonFestival',compact("invite_list","invite_phone"));
        }
    }

    /**
     * 国庆节活动
     * @return string
     */
    public function actionFestival20151001(){
        header("Content-type: text/html; charset=utf-8");
        echo "<script>alert('活动已经过期')</script>";
        echo "<script>window.location.href='//www.licaiwang.com'</script>";

        $invite_code = Yii::$app->request->get('code', '0');
        //验证邀请码的正确性
        $invite_info = member::get_invite_info($invite_code);
        if($invite_info['errorNum'] == '1'){
            //邀请链接有问题
            header("Content-type: text/html; charset=utf-8");
            echo "<script>alert('来源链接不合法')</script>";
            echo "<script>window.location.href='//www.licaiwang.com'</script>";
            return json_encode($invite_info);
        }
        $actibity_source = '国庆节活动';
        $invite_list = member::get_red_packet($invite_info['data']['invite_phone'],$actibity_source);

        $invite_phone = substr($invite_info['data']['invite_phone'],0,3).'****'.substr($invite_info['data']['invite_phone'],-4);

        if ($this->IsMobile()) {
            return $this->renderPartial('NationalDayM',compact("invite_list","invite_phone"));
        }
        else{

            return $this->renderPartial('NationalDay',compact("invite_list","invite_phone"));
        }

    }

    /**
     * 推广大师活动
     * @return string
     */
    public function actionFestival20151015(){
        $invite_code = Yii::$app->request->get('code', '0');
        //活动期间利率调整TODO
        date_default_timezone_set('PRC');
        //活动开始时间
        $begin_time = strtotime('2015-10-15');
        //活动结束时间
        $end_time = strtotime('2016-1-16');
        //当前时间
        $now_time = time();
        if($now_time >$end_time){
            header("Content-type: text/html; charset=utf-8");
            echo "<script>alert('活动已经过期')</script>";
            echo "<script>window.location.href='//www.licaiwang.com'</script>";
        }
        //解析链接中的手机号
        $invite_phone = \frontend\actions\App\AloneMethod::decrypt($invite_code);
        //判定手机号的合法性
        $res = UcenterMember::findOne([
            'phone' => $invite_phone,
            'username' => $invite_phone
        ]);
        if ($res == null) {
            header("Content-type: text/html; charset=utf-8");
            echo "<script>alert('来源链接不合法')</script>";
            echo "<script>window.location.href='//www.licaiwang.com'</script>";
        }
        $invite_phone = substr($invite_phone,0,3).'****'.substr($invite_phone,-4);
        if ($this->IsMobile()) {
            return $this->renderPartial('TheMasterM',compact("invite_phone"));
        }
        else{
            return $this->renderPartial('TheMasterM',compact("invite_phone"));
        }
    }
    /**
     * 发送手机验证码
     * @return string
     */
    public function actionSendcode(){
        $phone = $_POST['Phone'];
        $send = member::phonpreg_matchster($phone);
        return json_encode($send);
    }

    /**
     * 用户进行注册操作
     * @return string
     */
    public function actionSignup(){
        $post = Yii::$app->request->post();
        //获取邀请码
        $invite_code = $post['invite_code'];
        $actibity_source = $post['actibity_source'];
        //验证邀请码的正确性
        $invite_info = member::get_invite_info($invite_code);
        if($invite_info['errorNum']){
            //邀请链接有问题
            return json_encode($invite_info);
        }
        // 获取推荐人信息
        $invite_phone = $invite_info['data']['invite_phone'];
        $invitation_id = $invite_info['data']['invitation_id'];
        //创建红包
        member::create_read_packet($invite_phone,10,$actibity_source);
        //进行注册操作
        $phone = $post['phone'];
        $pwd = $post['password'];
        $phone_code = $post['validate_code'];
        $request = member::activity_register($phone,$pwd,$phone_code,$invitation_id,$actibity_source);
        return json_encode($request);
    }


    /**
     * 推广大师进行注册操作
     * @return string
     */
    public function actionSignupofmaster(){
        $post = Yii::$app->request->post();
        $userIp = Yii::$app->request->userIp;
        //获取邀请码
        $invite_code = $post['invite_code'];
        //解析链接中的手机号
        $invite_phone = \frontend\actions\App\AloneMethod::decrypt($invite_code);
        //判定手机号的合法性
        $res = UcenterMember::find()->where([
            'phone' => $invite_phone,
            'username' => $invite_phone
        ])->asArray()->one();
        if($res == null){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '活动链接来源不合法',
                'data' => null
            );
            return json_encode($return);
        }
        // 获取推荐人信息
        $invitation_id = $res['id'];
        //进行注册操作
        $phone = $post['phone'];
        $pwd = $post['password'];
        $phone_code = $post['validate_code'];
        $request = member::the_master_register($phone, $pwd, $phone_code,$invitation_id,$userIp);
        return json_encode($request);
    }

    public function actionCommon()
    {
        return $this->renderPartial('Common');
    }
    //推广页进来的用户
    public  function actionSignupofpromotion(){
        //进行注册操作
        $post = Yii::$app->request->post();
        $phone = $post['phone'];
        $pwd = $post['password'];
        $phone_code = $post['validate_code'];
        $from = (int)$post['url_code'];
        $request = member::the_promotion_register($phone, $pwd, $phone_code,$from);
        return json_encode($request);
    }
    /**
     * 判定用户使用的浏览器类型
     * @return bool
     */
    public function IsMobile(){
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
            return true;
        return false;
    }


}