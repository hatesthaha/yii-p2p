<?php
namespace frontend\actions;
use framework\App\Appcheck;
use common\models\base\session\Sessionkey;
use yii\base\ErrorException;
use Yii;
class App extends Action
{
    //将信息返回给app端
    public static function AppReturn1($data){
        $rsa = new Appcheck();
        $data = json_encode($data);
        $data = $rsa->encrypt_data1($data);//私钥加密
        $data = base64_encode($data);
        return $data;
    }

    //获取App传递过来的值
    public static function AppGet1()
    {
        $post = $_POST['data'];
        $ras = new Appcheck();
        $data = $ras->decrypt_data(base64_decode($post));
        if(!$data){
            return false;
        }
        $data_arr = json_decode($data, true);
        $sign = $data_arr['sign'];

        if (array_key_exists('sign', $data_arr)) {
            unset($data_arr['sign']);
        }
        $sign_data = $ras->decrypt_data(base64_decode($sign));
        $test = implode($data_arr);

        //进行sessionkey验证
        $sessionkey = self::chenk_sessionkey($data_arr['Sessionkey']);
        if(!$sessionkey){
            return false;
        }else{
            $uid = Sessionkey::find()->where(['sessionkey'=>$sessionkey])->asArray()->one();
            $uid = $uid['uid'];
            $data_arr['uid'] = $uid;
        }

        if ($sign_data == $test) {
           return $data_arr;
        }else{
            return false;
        }

    }

    //sessionkey验证
    private static function chenk_sessionkey($sessionkey){
        $is_sessionkey = Sessionkey::find()->where(['sessionkey'=>$sessionkey,'status'=>'1'])->asArray()->one();
        if(!$is_sessionkey){
            return false;
        }
        return $is_sessionkey['uid'];
    }

    /**
     * 判定用户session 是否失效
     * @param $sessionkey
     * @return bool
     */
    public static function sessionkey_istimeout($sessionkey){
        $is_timeout =  Sessionkey::find()->where(['sessionkey'=>$sessionkey,'status'=> Sessionkey::STATUS_DELETED])->asArray()->one();
        if($is_timeout){
            return true;
        }else{
            return false;
        }
    }



    //测试使用将信息返回给app端
    public static function AppReturn($data){
        //参数排序
//        ksort($data);
//        $data1 = json_encode($data);
//        $md = "123456";
//        $sign = md5($data1.$md);
//        $data['sign'] = $sign;

        $data = json_encode($data);
        $data = base64_encode($data);
        return $data;
    }

    //测试使用获取App传递过来的值
    public static function AppGet()
    {
        $request = Yii::$app->request;
        if(!$request->isPost){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '请post提交数据',
                'data' => null,
            );
            return $result;
        }
        if(!is_array($_POST) || !array_key_exists('data',$_POST)){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '请求格式错误',
                'data' => null,
            );
            return $result;
        }
        $post = $_POST['data'];
        $data = base64_decode($post);
        $data_arr = json_decode($data, true);
        //todo----对uid进行处理---测试下可以
//        if(array_key_exists('uid',$data_arr)){
//            unset($data_arr['uid']);
//        }
        //todo----对传递数据进行签名验证
        //进行sessionkey验证
        if(array_key_exists('sessionkey',$data_arr)){
            $sessionkey = self::chenk_sessionkey($data_arr['sessionkey']);
            if(!$sessionkey){
//                //判定用户是否是时效的sessionkey
                $is_timeout = self::sessionkey_istimeout($data_arr['sessionkey']);
                if($is_timeout){
                    $result = array(
                        'errorNum' => '2',
                        'errorMsg' => '请重新登录',
                        'data' => null,
                    );
                    return $result;
                }
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '用户尚未登陆',
                    'data' => null,
                );
                return $result;
            }else{
                $data_arr['uid'] = $sessionkey;
            }
        }
        $result = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $data_arr,
        );
        return $result;
    }

    //登录注册使用将信息返回给app端
    public static function AppReturn2($data){
        $data = json_encode($data);
        $data = base64_encode($data);
        return $data;
    }

    //登录注册使用获取App传递过来的值
    public static function AppGet2()
    {
        $post = $_POST['data'];
        $data = base64_decode($post);
        if(!$data){
            return false;
        }
        $data_arr = json_decode($data, true);
        $test = implode($data_arr);

        return $data_arr;


    }



}

