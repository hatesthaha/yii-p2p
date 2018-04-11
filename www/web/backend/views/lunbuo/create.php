<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\cms\CarouselFigure */

$this->title = 'Create Carousel Figure';
$this->params['breadcrumbs'][] = ['label' => 'Carousel Figures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carousel-figure-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
