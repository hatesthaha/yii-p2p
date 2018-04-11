<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaConfig */

$this->title = Yii::t('app', '更新 {modelClass}', [
    'modelClass' => '新浪配置',
]) ;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '新浪配置'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sina-config-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
