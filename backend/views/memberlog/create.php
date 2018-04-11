<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\ucenter\Log */

$this->title = Yii::t('app', 'Create Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
