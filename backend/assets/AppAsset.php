<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'adminlte/css/font-awesome.min.css',
        'adminlte/css/ionicons.min.css',
        'adminlte/css/AdminLTE.css',
        'adminlte/css/daterangepicker/daterangepicker-bs3.css',
    ];
    public $js = [
        'adminlte/js/AdminLTE/app.js',
        'adminlte/js/plugins/datepicker/bootstrap-datepicker.js',
        'adminlte/js/raphael-min.js',
        'adminlte/js/plugins/morris/morris.min.js',
        'adminlte/js/bootstrap.min.js',
        'adminlte/js//plugins/daterangepicker/moment.js',
        'adminlte/js//plugins/daterangepicker/daterangepicker.js',
        'adminlte/js/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js',
        'js/date.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
