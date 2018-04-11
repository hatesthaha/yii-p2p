<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Code */

$this->title = Yii::t('app', '创建');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '兑换码'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
