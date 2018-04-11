<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datetimepicker\DateTimePicker;
Use yii\helpers\Url;
use kartik\file\FileInput;
use common\models\base\fund\Thirdproduct;
use common\models\base\ucenter\Catmiddle;
/* @var $this yii\web\View */
/* @var $model common\models\base\fund\Thirdproduct */
/* @var $form yii\widgets\ActiveForm */

$octor = Catmiddle::find()->select('ucenter_member.id,uid,ucenter_member.username,ucenter_member.real_name')->joinWith(['user'])->andWhere(['cid'=>\common\models\UcenterMember::CUS_CRE])->asArray()->all();

foreach($octor as &$v){
    $v['newname'] = '('.$v['real_name'].')'.$v['username'];
}
$maxtor = Catmiddle::find()->select('ucenter_member.id,uid,ucenter_member.username,ucenter_member.real_name')->joinWith(['user'])->andWhere(['cid'=>\common\models\UcenterMember::CUS_MAXCRE])->asArray()->all();

foreach($maxtor as &$v){
    $v['newname'] = '('.$v['real_name'].')'.$v['username'];
}
?>
<?=Html::jsFile('@web/adminlte/js/jquery.min.js')?>

<div class="thirdproduct-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <input type="hidden" name="intent" value="<?= Thirdproduct::INTENT_CHECK ?>" />


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'intro')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'borrowman')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'creditor')->dropDownList(ArrayHelper::map($octor, 'id', 'newname')) ?>

    <?= $form->field($model, 'maxcreditor')->dropDownList(ArrayHelper::map($maxtor, 'id','newname')) ?>
    <?php
    echo '<label class="control-label">正式合同</label>';
    echo FileInput::widget([
        'model' => $model,
        'attribute' => 'contract',
        'pluginOptions' => [

            'uploadExtraData' => [
                'album_id' => 20,
                'cat_id' => 'Nature'
            ],
            'maxFileCount' => 10,
            'initialCaption'=> $model->realname,
            "showUpload"=> false,
        ]
    ]);
    ?>

    <?php
    echo '<label class="control-label">预期合同</label>';
    echo FileInput::widget([
        'model' => $model,
        'attribute' => 'intentcontract',
        'pluginOptions' => [

            'uploadExtraData' => [
                'album_id' => 20,
                'cat_id' => 'Nature'
            ],
            'maxFileCount' => 10,
            'initialCaption'=> $model->intentrealname,
            "showUpload"=> false,
        ]
    ]);
    ?>

    <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>


    <div class="form-group field-thirdproduct-start_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">起始时间</label>
        <?= DateTimePicker::widget([
            'model' => $model,
            'attribute' => 'start_at',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'linkFormat' => 'yyyy-mm-dd  HH:ii',
                'todayBtn' => true
            ]
        ]);?>
    </div>
    <div class="form-group field-thirdproduct-end_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">结束时间</label>
        <?= DateTimePicker::widget([
            'model' => $model,
            'attribute' => 'end_at',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'linkFormat' => 'yyyy-mm-dd  HH:ii ',
                'todayBtn' => true
            ]
        ]);?>
    </div>
    <?= $form->field($model, 'rate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
