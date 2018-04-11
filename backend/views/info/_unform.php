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
        <label class="control-label" for="info-freeze">可解冻资金</label>
        <input type="text" id="info-freeze" class="form-control" name="Info[freeze]" value="<?= $model->freeze ?>">

        <div class="help-block"></div>
    </div>
    <?= $form->field($model, 'balance')->textInput(['disabled' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php if( $model->sinamoney){ ?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>新浪账户余额</th>
            <th><?php echo $model->sinamoney['balance']; ?></th>
        </tr>
        </thead>
    </table>
<?php } ?>
<script>
    $(document).ready(function () {
        var freeze = $('#info-freeze').val();

        if(freeze<1){
            $('#info-freeze').attr("disabled","disabled");
        }
    });


</script>