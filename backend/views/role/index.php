<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create ') . Yii::t('app', 'Role'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'name',
                ],
                'description',
            [
                'attribute'=>'data',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->data =='' || $model->data == 0) {
                        return '<span class="label label-success">正常</span>';

                    }  else {
                        return '<span class="label label-danger">删除</span>';

                    }

                },

            ],
                [
                    //'header' => Yii::t('auth', 'Actions'),
                    'class' => 'yii\grid\ActionColumn',
                    //'dropdown' => false,
                    //'vAlign' => 'middle',
                    'urlCreator' => function ($action, $model, $key, $index) {
                            $link = '#';
                            switch ($action) {
                                case 'view':
                                    $link = Yii::$app->getUrlManager()->createUrl(['role/view', 'name' => $model->name]);
                                    break;
                                case 'update':
                                    $link = Yii::$app->getUrlManager()->createUrl(['role/update', 'name' => $model->name]);
                                    break;
                                case 'delete':
                                    $link = Yii::$app->getUrlManager()->createUrl(['role/delete', 'name' => $model->name]);
                                    break;
                            }
                            return $link;
                        },
                    //'viewOptions' => ['title' => Yii::t('auth', 'Details')],
                    //'updateOptions' => ['title' => Yii::t('auth', 'Edit page')],
                    //'deleteOptions' => ['title' => Yii::t('auth', 'Delete action')],
                ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{change}',
                'buttons' => [

                    'change' => function ($url, $model, $key) {
                        return $model->data == '-1'? Html::a('<span class="glyphicon glyphicon-list-alt"></span>', $url, ['title' => '恢复'] ):'';
                    },

                ],
                'headerOptions' => ['width' => '80'],
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
