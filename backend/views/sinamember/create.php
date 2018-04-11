<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaMember */

$this->title = Yii::t('app', 'Create Sina Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-member-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
