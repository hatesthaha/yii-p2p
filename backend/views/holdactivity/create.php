<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\activity\HoldActivity */

$this->title = 'Create Hold Activity';
$this->params['breadcrumbs'][] = ['label' => 'Hold Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hold-activity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
