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
            <th>用户名 </th>
            <th>项目</th>
            <th>金额</th>
            <th>购买时间</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($model as $item){ ?>
            <?php
            $product = Product::find()->where(['id'=>$item['product_id']])->asArray()->one();
            $member = UcenterMember::find()->where(['id'=>$item['member_id']])->asArray()->one();
            ?>
            <tr data-key="1">
                <td><?= $item['id']; ?></td>
                <td><?= $member['username']; ?></td>
                <td><?= $product['title']; ?></td>
                <td><?= $item['money']; ?></td>
                <td><?= date('Y-m-d H:i:s',$item['start_at']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?= LinkPager::widget(['pagination' => $pages]); ?>
</div>
