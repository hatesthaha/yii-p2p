<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\base\experience\Gold;
$octor = \common\models\UcenterMember::find()->asArray()->all();

foreach($octor as $k=>$v){
    $octor[$k]['newname'] = '('.$v['real_name'].')'.$v['username'];

}
/* @var $this yii\web\View */
/* @var $model common\models\base\experience\Gold */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gold-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->dropDownList(ArrayHelper::map($octor, 'id', 'newname')) ?>

    <?= $form->field($model, 'money')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '派发') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
