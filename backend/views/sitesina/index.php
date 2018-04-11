<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sinapay\SiteSinaBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '新浪网站账户对比';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sina-balance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('全部更新', ['upbalanceall'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'uid',
//            'identity_id',
            'phone',
            'user_name',
//             'bank_card',
             'site_balance',
             'sina_balance',
             'sina_available_balance',
             'user_earnings',
//             'sina_bonus',
             'sina_bonus_day',
             'sina_bonus_month',
             'sina_bonus_sum',
             'create_time',
//             'status',
//             'msg',
            // 'create_at',
            // 'update_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
