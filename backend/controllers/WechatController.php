<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;

class WechatController extends Controller{
    public $enableCsrfValidation = false;
    public $types = array(
        'view', 'click', 'scancode_push',
        'scancode_waitmsg', 'pic_sysphoto', 'pic_photo_or_album',
        'pic_weixin', 'location_select'
    );
    public function actionIndex(){
        $wechat = \App::$app->wechat;
        $menus =$wechat->getMenuList();
        foreach($menus as $k => $v){
            $menus[$k]['subMenus'] = $v['sub_button'];
        }

        return $this->render('index',[
            'menus' => $menus
        ]);
    }
    public function actionAddmenu(){
        $arr = [];
        $dat = $this->menuBuildMenuSet(json_decode(file_get_contents( "php://input"),true));
        $aa = \App::$app->wechat->createMenu(
            $dat
        );

        if($aa){
            echo 'success';
        }


    }
    private function menuBuildMenuSet($menu) {
        error_reporting(0);
        $set = array();
        $set['button'] = array();
        foreach($menu as $vs) {
            foreach($vs as $m) {
                $entry = array();
                $entry['name'] = urlencode($m['name']);

                if (!empty($m['subMenus'])) {

                    $entry['sub_button'] = array();
                    foreach ($m['subMenus'] as $s) {
                        $e = array();
                        if ($s['type'] == 'url') {
                            $e['type'] = 'view';
                        } elseif (in_array($s['type'], $this->types)) {
                            $e['type'] = $s['type'];
                        } else {
                            $e['type'] = 'click';
                        }
                        $e['name'] = urlencode($s['name']);
                        if ($e['type'] == 'view') {
                            $e['url'] = urlencode($s['url']);
                        } else {
                            $e['key'] = urlencode($s['forward']);
                        }
                        $entry['sub_button'][] = $e;
                    }
                } else {
                    if ($m['type'] == 'url') {
                        $entry['type'] = 'view';
                    } elseif (in_array($m['type'], $this->types)) {
                        $entry['type'] = $m['type'];
                    } else {
                        $entry['type'] = 'click';
                    }
                    if ($entry['type'] == 'view') {
                        $entry['url'] = $m['url'];
                    } else {
                        $entry['key'] = urlencode($m['forward']);
                    }

                }
                $set['button'][] = $entry;
            }
        }
        return $set['button'];
    }
}