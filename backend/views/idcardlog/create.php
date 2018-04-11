<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\site\IdcardLog */

$this->title = Yii::t('app', 'Create Idcard Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Idcard Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="idcard-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
