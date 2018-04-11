<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\base\asset\Info */
/* @var $form yii\widgets\ActiveForm */

?>
<?=Html::jsFile('@web/adminlte/js/jquery.min.js')?>
<div class="info-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="form-group field-info-balance">
        <label class="control-label" for="info-username">会员</label>
        <?=$model->member->username ?>

        <div class="help-block"></div>
    </div>

    <div class="form-group field-info-balance">
        <label class="control-label" for="info-balance">提取金额</label>
        <input type="text" id="info-balance" class="form-control" name="Info[fabalance]" value="">

        <div class="help-block"></div>
    </div>
    <?= $form->field($model, 'balance')->textInput(['disabled' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).ready(function () {


    });


</script>