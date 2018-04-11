<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datetimepicker\DateTimePicker;
Use yii\helpers\Url;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model common\models\base\fund\Thirdproduct */
/* @var $form yii\widgets\ActiveForm */
?>
<?=Html::jsFile('@web/adminlte/js/jquery.min.js')?>

<div class="thirdproduct-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>



    <?= $form->field($model, 'reject')->textarea(['rows' => 6]) ?>



    <div class="form-group">
        <?= Html::submitButton( Yii::t('app', '驳回') , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
