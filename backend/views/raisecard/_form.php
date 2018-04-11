<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use common\models\base\activity\RaiseCard;
/* @var $this yii\web\View */
/* @var $model common\models\base\activity\RaiseCard */
/* @var $form yii\widgets\ActiveForm */
$octor = \common\models\UcenterMember::find()->asArray()->all();

foreach($octor as $k=>$v){
    $octor[$k]['newname'] = '('.$v['real_name'].')'.$v['username'];

}

?>

<div class="raise-card-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'member_id')->dropDownList(ArrayHelper::map($octor, 'id', 'newname')) ?>
    <div class="form-group field-thirdproduct-start_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">有效期开始时间</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'validity_start_at',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true
            ]
        ]);?>
    </div>
    <div class="form-group field-thirdproduct-start_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">有效期结束时间</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'validity_out_at',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true
            ]
        ]);?>
    </div>


    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(RaiseCard::labels()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
