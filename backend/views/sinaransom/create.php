<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaRansom */

$this->title = Yii::t('app', 'Create Sina Ransom');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Ransoms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-ransom-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
