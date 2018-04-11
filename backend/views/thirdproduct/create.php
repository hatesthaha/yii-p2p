<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\fund\Thirdproduct */

$this->title = Yii::t('app', '创建已购债权审核');
$this->params['breadcrumbs'][] = ['label' => "创建已购债权审核", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thirdproduct-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
