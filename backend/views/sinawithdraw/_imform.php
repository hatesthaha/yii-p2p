<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use common\models\base\activity\Code;
use common\models\base\activity\Card;
/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Code */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="code-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group field-code-num">
        <label class="control-label" for="code-num">需要退款用户</label>
        <input type="text" id="code-num" class="form-control" name="Code[uid]" maxlength="64">

        <label class="control-label" for="code-num">需要退款订单号</label>
        <input type="text" id="code-num" class="form-control" name="Code[out_trade_no]" maxlength="64">

        <label class="control-label" for="code-num">退款金额</label>
        <input type="text" id="code-num" class="form-control" name="Code[money]" maxlength="64">

        <label class="control-label" for="code-num">退款原因</label>
        <input type="text" id="code-num" class="form-control" name="Code[msg]" maxlength="64">

        <div class="help-block"></div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', '提交'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
