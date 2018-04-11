<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sinapay\SinaConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sina Configs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-config-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Sina Config'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sinapay_site_prefix',
            'sinapay_version',
            'sinapay_partner_id',
            'sign_type',
            // 'sinapay_md5_key',
            // 'sinapay_input_charset',
            // 'sinapay_rsa_sign_private_key:ntext',
            // 'sinapay_rsa_sign_public_key:ntext',
            // 'sinapay_rsa_public__key:ntext',
            // 'sinapay_mgs_url:url',
            // 'sinapay_mas_url:url',
            // 'sinapay_site_email:email',
            // 'sinapay_give_accrual',
            // 'create_at',
            // 'update_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
