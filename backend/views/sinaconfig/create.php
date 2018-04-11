<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaConfig */

$this->title = Yii::t('app', 'Create Sina Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
