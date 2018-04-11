<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\invation\InvitationCode */

$this->title = Yii::t('app', 'Create Invitation Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invitation Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invitation-code-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
