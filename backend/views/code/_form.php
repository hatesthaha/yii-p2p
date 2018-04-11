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

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coupon_id')->dropDownList(ArrayHelper::map(Card::find()->asArray()->all(), 'id', 'title'))?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
