<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\activity\RaiseCard */

$this->title = Yii::t('app', '创建增息卡');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '增息卡'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raise-card-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
