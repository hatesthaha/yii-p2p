<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use xj\ueditor\Ueditor;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(\backend\models\User::find()->asArray()->all(), 'id', 'username')) ?>

    <?php
    echo '<label class="control-label">图片</label>';
    echo FileInput::widget([
        'model' => $model,
        'attribute' => 'logo',
        'pluginOptions' => [

            'uploadExtraData' => [
                'album_id' => 20,
                'cat_id' => 'Nature'
            ],
            'maxFileCount' => 10,
            'initialCaption'=> $model->logo,
            "showUpload"=> false,
        ]
    ]);
    ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\backend\models\Category::get(0, \backend\models\Category::find()->asArray()->all()), 'id', 'str_label')) ?>
    <?= $form->field($model, 'status')->dropDownList(\backend\models\Article::getArrayStatus()) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'intro')->textarea(['rows' => 6]) ?>



    <?php
    //外部TAG
    echo Html::tag('script', $model->content, [
        'id' => Html::getInputId($model, 'content'),
        'name' => Html::getInputName($model, 'content'),
        'type' => 'text/plain',
    ]);
    echo Ueditor::widget([
        'model' => $model,
        'attribute' => 'content',
        'renderTag' => false,
        'jsOptions' => [
            'serverUrl' => yii\helpers\Url::to(['upload']),
            'autoHeightEnable' => true,
            'autoFloatEnable' => true
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
