<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\post\SignInSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '签到记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sign-in-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            [
                'attribute' => 'sign_in_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],

            'sign_in_money',
            'sign_in_ip',
            // 'sign_in_from',
            // 'create_at',
            // 'update_at',


        ],
    ]); ?>

</div>
