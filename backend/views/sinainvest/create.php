<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaInvest */

$this->title = Yii::t('app', 'Create Sina Invest');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Invests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-invest-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
