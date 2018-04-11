<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\ucenter\Cat */

$this->title = Yii::t('app', '创建会员分类');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '会员分类'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
