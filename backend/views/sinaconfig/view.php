<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaConfig */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sinapay_site_prefix',
            'sinapay_version',
            'sinapay_partner_id',
            'sign_type',
            'sinapay_md5_key',
            'sinapay_input_charset',
            'sinapay_rsa_sign_private_key:ntext',
            'sinapay_rsa_sign_public_key:ntext',
            'sinapay_rsa_public__key:ntext',
            'sinapay_mgs_url:url',
            'sinapay_mas_url:url',
            'sinapay_site_email:email',
            'sinapay_give_accrual',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
