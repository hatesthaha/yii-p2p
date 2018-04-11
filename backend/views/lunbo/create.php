<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\cms\Lunbo */

$this->title = '创建轮播图';
$this->params['breadcrumbs'][] = ['label' => 'Lunbos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lunbo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
