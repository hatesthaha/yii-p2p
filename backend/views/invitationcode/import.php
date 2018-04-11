<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Code */

$this->title = Yii::t('app', '批量生成');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '邀请码'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-create">

    <?= $this->render('_imform', [
        'model' => $model,
    ]) ?>

</div>
