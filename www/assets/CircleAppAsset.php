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

class CircleAppAsset extends AssetBundle
{
    public $sourcePath = '@almasaeed/';
    public $css = [
    ];
    public $js = [
    	'js/raphael-min.js',
    ];
}
