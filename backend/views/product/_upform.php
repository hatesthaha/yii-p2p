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
use common\models\fund\FundProductThirdproduct;
use common\models\base\fund\Thirdproduct;
$id = $model->id;
$thirdfundModel = [];
$modelmid = FundProductThirdproduct::find()->where(['product_id' => $id])->asArray()->all();
if($modelmid){
    foreach ($modelmid as $v)
    {
        array_push($thirdfundModel,Thirdproduct::find()->select('id')->where(['id' => $v['thirdproduct_id']])->asArray()->one());
    }
    $newth = [];
    foreach($thirdfundModel as $k=>$v){
        array_push($newth,$v['id']);
    }
}

/* @var $this yii\web\View */
/* @var $model common\models\base\fund\product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'type')->dropDownList(['0' => '债权项目', '1' => '债权转让项目'],[ 'onchange'=>'display()'])?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div id="creditor_amount" class="field-product-amount" style="display: <?php echo empty($newth)? 'block':'none'; ?>;">
        <label class="control-label" for="_creditor_amount">项目总额</label>
        <input type="text" id="_creditor_amount" class="form-control" name="Product[amount]" value="<?=$model->amount ?>">
        <div class="help-block"></div>
    </div>

    <div id="creditor_transfer" class="field-product-temp_products" style="display: <?php echo $model->type==\common\models\base\fund\Product::TYPE_THIRD? 'block':'none'; ?>;">
        <label class="control-label" for="product-temp_products">第三方投资项目</label>
        <input type="hidden" name="Product[temp_products]" value="11">
        <select id="product-temp_products" class="form-control" name="Product[temp_products][]" multiple="multiple" size="4" style="font-size:14px;">
            <?php
            foreach($products as $k=>$v){
            ?>
            <option value="<?= $v['id'] ?>" <?php echo in_array($v['id'],$newth)? 'selected':''; ?>><?= $v['title'] ?></option>
            <?php
            }
            ?>

        </select>

        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'ocreditor',['options' => [ 'style'=>'display:block;','id'=> 'ocreditor']])->dropDownList(ArrayHelper::map(\common\models\UcenterMember::find()->andWhere(['type'=>\common\models\UcenterMember::CUS_CRE])->asArray()->all(), 'id', 'username')) ?>
    <?= $form->field($model, 'maxcreditor',['options' => [ 'style'=>'display:block;','id'=> 'maxcreditor']])->dropDownList(ArrayHelper::map(\common\models\UcenterMember::find()->andWhere(['type'=>\common\models\UcenterMember::CUS_MAXCRE])->asArray()->all(), 'id', 'username')) ?>
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
                'format' => 'yyyy-mm-dd  HH:ii ',
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
                'format' => 'yyyy-mm-dd  HH:ii ',
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

			document.getElementById("creditor_amount").style.display = "block";
			document.getElementById("creditor_transfer").style.display = "none";
		}
		else if(selected.options[selected.selectedIndex].value == '1')
		{

			document.getElementById("creditor_transfer").style.display = "block";
			document.getElementById("creditor_amount").style.display = "none";
		}
	}
</script>
