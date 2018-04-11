<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\fund\product */

$this->title = Yii::t('app', '创建投资项目');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '项目'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <?= $this->render('_form', [
        'model' => $model,
    	'products' =>$products,
    ]) ?>

</div>
