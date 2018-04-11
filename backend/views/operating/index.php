<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\site\OperatingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '日志记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operating-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'step',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d']

            ],


        ],
    ]); ?>

</div>
