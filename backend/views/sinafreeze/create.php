<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaFreeze */

$this->title = Yii::t('app', 'Create Sina Freeze');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Freezes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-freeze-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
