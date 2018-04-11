<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\invation\AssetConfig */

$this->title = Yii::t('app', 'Create Asset Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Asset Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
