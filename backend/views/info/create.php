<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\asset\Info */

$this->title = Yii::t('app', 'Create Info');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="info-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
