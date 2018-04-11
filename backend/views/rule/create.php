<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\experience\Rule */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '体验金规则'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rule-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
