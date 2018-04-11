<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\category;
/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */

//fix the issue that it can assign itself as parent

$parentCatalog = ArrayHelper::merge([0 =>'根分类'], ArrayHelper::map(Category::get(0, Category::find()->all()), 'id', 'str_label'));
unset($parentCatalog[$model->id]);
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'pai')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'intro')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList($parentCatalog) ?>

    <?= $form->field($model, 'status')->dropDownList(Category::getArrayStatus()) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
