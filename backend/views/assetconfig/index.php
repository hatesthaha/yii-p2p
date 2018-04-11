<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invation\AssetConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Asset Configs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-config-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Asset Config'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'deposit_num',
            'deposit_min',
            'deposit_max',
            'invest_num',
            // 'invest_min',
            // 'invest_max',
            // 'withdraw_num',
            // 'withdraw_min',
            // 'withdraw_max',
            // 'ransom_num',
            // 'ransom_min',
            // 'ransom_max',
            // 'create_at',
            // 'update_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
