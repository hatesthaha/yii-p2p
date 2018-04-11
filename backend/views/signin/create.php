<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\post\SignIn */

$this->title = Yii::t('app', 'Create Sign In');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sign Ins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sign-in-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
