<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\cms\Cat */

$this->title = Yii::t('app', '创建');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '信息分类'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
