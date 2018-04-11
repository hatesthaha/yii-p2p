<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UcenterMember */

$this->title = Yii::t('app', 'Create Ucenter Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ucenter Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ucenter-member-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
