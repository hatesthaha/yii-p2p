<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UcenterMember;
use yii\helpers\ArrayHelper;
Use yii\helpers\Url;
use kartik\file\FileInput;
use common\models\base\ucenter\Cat;
use common\models\base\ucenter\Catmiddle;
/* @var $this yii\web\View */
/* @var $model common\models\UcenterMember */
/* @var $form yii\widgets\ActiveForm */
$allcat = Cat::find()->all();
$cattype = Catmiddle::find()->where(['uid'=>$model->id])->asArray()->all();
$cat = [];
foreach($cattype as $k=>$v){
    $newname = Cat::find()->where(['id'=>$v['cid']])->asArray()->one();
    array_push($cat,$newname['id']);
}

?>

<div class="ucenter-member-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php
    echo '<label class="control-label">头像</label>';
    echo FileInput::widget([
        'model' => $model,
        'attribute' => 'person_face',
        'pluginOptions' => [

            'uploadExtraData' => [
                'album_id' => 20,
                'cat_id' => 'Nature'
            ],
            'maxFileCount' => 10,
            'initialCaption'=> $model->person_face,
            "showUpload"=> false,
        ]
    ]);
    ?>


    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(UcenterMember::getArrayStatus()) ?>

    <?= $form->field($model, 'lock')->dropDownList(UcenterMember::getTypeStatus()) ?>

    <div class="form-group field-ucentermember-type">
        <label class="control-label" for="ucentermember-type">类型</label>
        <input type="hidden" name="UcenterMember[type]" value="">
        <select id="ucentermember-type" class="form-control" name="UcenterMember[type][]" multiple="multiple" size="4" style="font-size:14px;">
            <?php
            foreach($allcat as $k=>$v){
            ?>
            <option value="<?php echo $v['id']; ?>" <?php echo in_array($v['id'],$cat)? 'selected':''; ?> ><?php echo $v['name']; ?></option>
            <?php
            }
            ?>

        </select>

        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'create_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_channel')->textInput() ?>

    <?= $form->field($model, 'parent_member_id')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
