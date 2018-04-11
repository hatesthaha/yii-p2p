<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\cms\Link */

$this->title = Yii::t('app', '创建');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '网站信息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
