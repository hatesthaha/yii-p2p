<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\site\IdcardLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '身份认证信息');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="idcard-log-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'uid',
            'name',
            'idcard',
//            'status',
            // 'address',
            // 'sex',
            // 'birthday',
             'remark',
            // 'create_at',
            // 'update_at',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
