<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\setting\BankList */

$this->title = '新建银行限制';
$this->params['breadcrumbs'][] = ['label' => 'Bank Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
