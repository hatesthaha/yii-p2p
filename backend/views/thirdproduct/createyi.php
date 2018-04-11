<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\fund\Thirdproduct */

$this->title = Yii::t('app', '创建意向债权');
$this->params['breadcrumbs'][] = ['label' => "创建意向债权", 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', '创建意向债权');
?>
<div class="thirdproduct-create">


    <?= $this->render('_yiform', [
        'model' => $model,
    ]) ?>

</div>
