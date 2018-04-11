<?php
namespace frontend\actions;
use common\models\post\ApiPost;
use frontend\actions\app\member;
use yii\base\Behavior;
use yii\web\Controller;
use Yii;

class BCrdf extends Behavior
{
    public $actions = [];
    public $controller;
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }
    public function beforeAction($event)
    {
        $action = $event->action->id;
            if(in_array($action, $this->actions)){
                $this->controller->enableCsrfValidation = false;
                //记录接口记录
                if($action != 'returnurl'){
                    if(is_array($_POST) && array_key_exists('data',$_POST)){
                        // 根据ip获取地区
                        $area = member::get_area(Yii::$app->request->userIp);
                        $area = $area ? $area : '地球';
                        $post_from = '';
                        $post_vresion = '';
                        $post_data =  base64_decode($_POST['data']);
                        $post_array =  json_decode($post_data, true);
                        if(array_key_exists('post_from',$post_array) && array_key_exists('post_vresion',$post_array)){
                            $post_from = $post_array['post_from'];
                            $post_vresion = $post_array['post_vresion'];
                        }
                        $log = new ApiPost();
                        $log->action = $action;
                        $log->post_data = $post_data;
                        $log->post_from = $post_from;
                        $log->post_version = $post_vresion;
                        $log->post_ip = Yii::$app->request->userIp;
                        $log->post_time = date('Y-m-d-H-i-s', time());
                        $log->post_area = $area;
                        $log->save();
                    }
                }

            }



    }
}