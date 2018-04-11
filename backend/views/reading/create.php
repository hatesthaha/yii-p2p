<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\cms\ReadingLog */

$this->title = '创建阅读记录信息';
$this->params['breadcrumbs'][] = ['label' => '用户阅读记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reading-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
