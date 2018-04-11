<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\cms\Feedback */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '反馈意见'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
