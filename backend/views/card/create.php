<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Card */

$this->title = Yii::t('app', '创建');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '增息卡'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
