<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use common\models\UcenterMember;
use common\models\base\fund\Product;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '订单列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>债权名 </th>
            <th>创建人</th>
            <th>金额</th>
            <th>已获得投资金额</th>
            <th>债权开始时间</th>
            <th>债权结束时间</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($list as $item){ ?>

            <tr data-key="1">
                <td><?= $item['id']; ?></td>
                <td><?= $item['title']; ?></td>
                <td><?= $item['creditor']; ?></td>
                <td><?= $item['amount']; ?></td>
                <td><?= $item['invest_sum']; ?></td>
                <td><?= date('Y-m-d H:i:s',$item['start_at']); ?></td>
                <td><?= date('Y-m-d H:i:s',$item['end_at']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
