<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\ucenter\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '登录日志');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],



            [
                'attribute' => 'member_id',
                'value'=>function ($model) {
                    return $model->member ? $model->member->username : '-';
                },
            ],
            'login_ip',
            [
                'attribute' => 'login_time',
                'format' => ['date', 'php:Y-m-d']
            ],
            'login_area',
            // 'status',
            // 'create_at',
            // 'update_at',


        ],
    ]); ?>

</div>
