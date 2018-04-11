<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Code */

$this->title = Yii::t('app', '新浪中间账户退款');
?>
< class="code-create">

    <?= $this->render('_imform') ?>

   uid

    <?= DetailView::widget([
        'model' => $data,
        'attributes' => [
            'available_balance',
            'site_balabce',
            'available_balance_now',
            'site_balabce_now'
        ],
    ]) ?>

</div>
