<?php
/**
 * @company :万虎网络
 * @author:liushaohua
 * @time: 2015年7月14日 11:36:26
 * 
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use dosamigos\datetimepicker\DateTimePicker;
use common\models\base\ucenter\Catmiddle;

/* @var $this yii\web\View */
/* @var $model common\models\base\fund\product */
/* @var $form yii\widgets\ActiveForm */
$octor = Catmiddle::find()->select('ucenter_member.id,uid,ucenter_member.username,ucenter_member.real_name')->joinWith(['user'])->andWhere(['cid'=>\common\models\UcenterMember::CUS_CRE])->asArray()->all();
//$octor = \common\models\UcenterMember::find()->andWhere(['type'=>\common\models\UcenterMember::CUS_CRE])->asArray()->all();
foreach($octor as $k=>$v){
    $octor[$k]['newname'] = '('.$v['real_name'].')'.$v['username'];
    $octor[$k]['user'] = '';

}
$maxtor = Catmiddle::find()->select('ucenter_member.id,uid,ucenter_member.username,ucenter_member.real_name')->joinWith(['user'])->andWhere(['cid'=>\common\models\UcenterMember::CUS_MAXCRE])->asArray()->all();

//$maxtor = \common\models\UcenterMember::find()->andWhere(['type'=>\common\models\UcenterMember::CUS_MAXCRE])->asArray()->all();

foreach($maxtor as $k=>$v){
    $maxtor[$k]['newname'] = '('.$v['real_name'].')'.$v['username'];
}
//var_dump($maxtor);
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'type')->dropDownList(['0' => '债权项目', '1' => '债权转让项目'],[ 'onchange'=>'display()'])?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'virtual_amonnt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'virtual_invest_people')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'amount',['options' => [ 'style'=>'display:block;','id'=> 'creditor_amount']])->textInput([ 'value'=>'','id'=> '_creditor_amount']) ?>

    <?= $form->field($model, 'ocreditor',['options' => [ 'style'=>'display:block;','id'=> 'ocreditor']])->dropDownList(ArrayHelper::map($octor, 'id', 'newname')) ?>

    <?= $form->field($model, 'maxcreditor',['options' => [ 'style'=>'display:block;','id'=> 'maxcreditor']])->dropDownList(ArrayHelper::map($maxtor, 'id','newname')) ?>

    <?= $form->field($model, 'temp_products',['options' => [ 'style'=>'display:none;','id'=> 'creditor_transfer']])->dropDownList(ArrayHelper::map($products, 'id', 'title'),['multiple'=>'multiple','style'=>'font-size:14px;']) ?>

    <?= $form->field($model, 'intro')->textarea(['rows' => 6]) ?>

	<div class="form-group field-thirdproduct-start_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">起始时间</label>
        <?= DateTimePicker::widget([
            'model' => $model,
            'attribute' => 'start_at',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'linkFormat' => 'yyyy-mm-dd  HH:ii ',
                'todayBtn' => true
            ]
        ]);?>
    </div>

    	<div class="form-group field-thirdproduct-start_at required" style="width: 300px">
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
            'initialCaption'=> $model->contract,
            'maxFileCount' => 10,
            "showUpload"=> false,
        ]
    ]);
    ?>

    <?= $form->field($model, 'each_max')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'each_min')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script language="javascript">
	function display()
	{
		var selected = document.getElementById("product-type");
		if(selected.options[selected.selectedIndex].value == '0')
		{
			for(var i=0;i<document.getElementById("product-temp_products").length;i++){
				document.getElementById("product-temp_products").options[i].selected=false;
				}
            document.getElementById("ocreditor").style.display = "block";
            document.getElementById("maxcreditor").style.display = "block";
			document.getElementById("creditor_amount").style.display = "block";
			document.getElementById("creditor_transfer").style.display = "none";
		}
		else if(selected.options[selected.selectedIndex].value == '1')
		{
            document.getElementById("ocreditor").style.display = "none";
            document.getElementById("maxcreditor").style.display = "none";
			document.getElementById("_creditor_amount").value = "";
			document.getElementById("creditor_transfer").style.display = "block";
			document.getElementById("creditor_amount").style.display = "none";
		}
	}
</script>
