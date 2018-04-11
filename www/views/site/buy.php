<?php
use frontend\actions\Invest;
use yii\base\ErrorException;
$uid = Yii::$app->user->id;
$pid = $_POST['pid'];
$money = $_POST['money'];
try 
{
	$result = Invest::invest($uid, $pid, $money);
	if($result)
	{
		echo '购买成功';
		exit;
	}
}
catch (ErrorException $e)
{
	echo $e->getMessage();
	exit;
}

?>