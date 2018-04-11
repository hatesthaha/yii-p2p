<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaWithdraw */

$this->title = Yii::t('app', 'Create Sina Withdraw');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Withdraws'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-withdraw-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
