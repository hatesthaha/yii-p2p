<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace www\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

class AppAsset extends AssetBundle
{
    public $sourcePath = '@almasaeed/';
    public $css = [
        'css/base.css',
    	'css/font-awesome.min.css',
    	'css/index.css',
    	'css/zy.css',
    ];
    public $js = [
        'js/jquery-1.9.1.min.js',
    	'js/responsiveslides.min.js',
    	'js/slide.js',
    	'js/prevnext.js',
    ];
}
