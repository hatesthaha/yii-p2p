<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\activity\RedPackets */

$this->title = Yii::t('app', 'Create Red Packets');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Red Packets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="red-packets-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
