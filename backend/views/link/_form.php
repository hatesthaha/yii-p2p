<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\base\cms\Link;
Use yii\helpers\Url;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model common\models\base\cms\Link */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="link-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'cat_id')->dropDownList(Link::getArrayCats()) ?>

    <?= $form->field($model, 'intro')->textarea(['rows' => 6]) ?>

    <?php
    echo '<label class="control-label">图片</label>';
    echo FileInput::widget([
        'model' => $model,
        'attribute' => 'bannar',
        'pluginOptions' => [

            'uploadExtraData' => [
                'album_id' => 20,
                'cat_id' => 'Nature'
            ],
            'maxFileCount' => 10,
            'initialCaption'=> $model->bannar,
            "showUpload"=> false,
        ]
    ]);
    ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Link::getArrayStatus()) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
