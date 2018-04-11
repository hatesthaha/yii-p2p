<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\experience\Gold */

$this->title = Yii::t('app', '派发体验金');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '体验金'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gold-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
