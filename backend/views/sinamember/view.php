<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaMember */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-member-view">
    <?php if( $model->sinamoney){ ?>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>新浪账户余额</th>
                <th><?php echo $model->sinamoney['balance']; ?></th>
            </tr>
            </thead>
        </table>
    <?php } ?>

</div>
