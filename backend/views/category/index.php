<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>标题 </th>
            <th>链接</th>
            <th>简介</th>
            <th>状态</th>
            <th>编辑</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($dataProvider as $item){ ?>
            <tr data-key="1">
                <td><?= $item['id']; ?></td>
                <td><?= $item['str_label']; ?></td>
                <td><?= $item['link']; ?></td>
                <td><?= $item['intro']; ?></td>
                <td><?php
                    if ($item['status'] ===\backend\models\Category::STATUS_ACTIVE) {
                       echo  '<span class="label label-success">正常</span>';
                    } else {
                        echo '<span class="label label-danger">删除</span>';
                    }
                    ?></td>
                <td>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['category/create','parent_id'=>$item['id']]); ?>" title="添加子栏目" data-pjax="0"><span class="glyphicon glyphicon-plus-sign"></span></a>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['category/view','id'=>$item['id']]); ?>"" title="查看" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['category/update','id'=>$item['id']]); ?>"" title="编辑" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
                    <?php if ($item['status'] ===\backend\models\Category::STATUS_ACTIVE) { ?>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['category/delete','id'=>$item['id']]); ?>" title="删除" data-confirm="确定要删除此栏目吗？" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-trash"></span></a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
