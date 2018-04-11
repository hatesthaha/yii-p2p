<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\fund\ThirdproductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '债权审核');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thirdproduct-index">

<?php $form=ActiveForm::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= html::submitButton('审核', ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],

            'title',
            'intro',
            'source',
            [
                'attribute' => 'creditor',
                'value'=>function ($model) {
                    return $model->typeocUser ? $model->typeocUser->username : '-';
                },
            ],
            [
                'attribute' => 'maxcreditor',
                'value'=>function ($model) {
                    return $model->typemaxUser ? $model->typemaxUser->username : '-';
                },
                'headerOptions' => ['width' => '100'],
            ],
            // 'contract',
            // 'remarks',
            // 'amount',
            // 'start_at',
            // 'end_at',
            // 'rate',
            // 'invest_people',
            // 'invest_sum',
            // 'create_at',
            // 'update_at',
            // 'status',
            // 'create_user_id',
            // 'check_user_id',

            ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{reject}',
                'buttons' => [
                    'reject' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;', $url, ['title' => '驳回'] );
                    },
                ],
                'headerOptions' => ['width' => '80'],
            ],
        ],
    ]); ?>
<?php ActiveForm::end(); ?>
</div>
