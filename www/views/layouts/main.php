<?php
use yii\helpers\Html;
use common\models\base\fund\Income;
use common\models\setting\Setting;
use common\models\base\asset\Info;
/* @var $this \yii\web\View */
/* @var $content string */



	$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
	//收益数据包
	if(!yii::$app->user->isGuest)
	{
		$income_array = Income::find()->where("created_at>=".strtotime(date("Y-m-d"))." AND member_id=".yii::$app->user->id)->asArray()->one();
		$profit = Info::findOne(['member_id'=>yii::$app->user->id])->profit;
		$income_total = Info::findOne(['member_id'=>yii::$app->user->id])->total_revenue;
	}
	else 
	{
		$income_array = "";
		$profit = "";
		$income_total = "";
	}
	// footer数据表
	try
	{
		$tel1 = Setting::findOne(['code'=>'phone'])->value;
		$tel2 = Setting::findOne(['code'=>'tell'])->value;
		$email = Setting::findOne(['code'=>'email'])->value;
		$work = Setting::findOne(['code'=>'work'])->value;
	}
	catch (ErrorException $e)
	{
		$tel1 = "";
		$tel2 = "";
		$email = "";
		$work = "";
	}
	//关键词
	$keywords = '';
	try
	{
		$keywords = Setting::findOne(['code'=>'siteKeyword'])->value; 
	}
	catch (ErrorException $e)
	{
	
	}
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>

        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        
        <script>
            var _hmt = _hmt || [];
            (function() {
                var hm = document.createElement("script");
                hm.src = "//hm.baidu.com/hm.js?f7612601de603500274538188dc82daf";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();
        </script>
        <link rel="shortcut icon" href="<?php echo $directoryAsset;?>/images/favicon2.ico"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title).' - 理财王 - 活期理财，我们更专业' ?></title>
        <?php $this->registerMetaTag(['name'=>'keywords','content'=>$keywords]);?>
		<?php $this->registerMetaTag(['name'=>'description','content'=>'理财王是一家专注于活期理财的互联网金融公司，为广大用户提供安全、便捷、灵活的活期理财项目，是互联网+时代高效实用稳健的互联网金融服务平台。']);?>
        <?php $this->head() ?>
        
    <?php 
    	\www\assets\AppAsset::register($this);
    ?>

    </head>
    <body class="skin-blue">
    <?php $this->beginBody() ?>
    <div class="wrapper">
       	<?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset,'income_array'=>$income_array,'profit'=>$profit,'income_total'=>$income_total]
        ) ?>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <?php
            echo  $this->render(
                'content.php',
                ['content' => $content, 'directoryAsset' => $directoryAsset]
            );
//            	if(yii::$app->controller->id != 'help')
//            	{
//           				echo  $this->render(
//           						'content.php',
//           						['content' => $content, 'directoryAsset' => $directoryAsset]
//           				);
//            	}
//            	else
//            	{
//            		$this->beginContent('@www/views/layouts/help_left.php');
//            			echo $content;
//            		$this->endContent();
//            		//echo $this->renderFile('@www/views/layouts/help_left.php');
//            	}
             ?>
            <?php if(yii::$app->controller->action->id != 'signup' && yii::$app->controller->action->id != 'login') {?>
            <?= $this->render(
                'footer.php',compact("tel1","tel2","email","work","content")
            ) ?>
            <?php } else {?>
            <?= $this->render(
                'footer2.php',
                ['content' => $content, 'directoryAsset' => $directoryAsset]
            ) ?>
            <?php }?>

        </div>
    </div>
    <?php $this->endBody() ?>
    <?php if (isset($this->blocks['jquery_script'])): ?>
        <?= $this->blocks['jquery_script'] ?>
    <?php endif; ?>
    </body>
    </html>
    <?php $this->endPage() ?>

