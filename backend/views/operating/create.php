<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\base\site\Operating */

$this->title = Yii::t('app', 'Create Operating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operatings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operating-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
