<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\actions\sinapay;
use common\models\base\asset\Info;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
//判断用户是否绑定银行卡
$is_bind = sinapay::isBinding($user->id);
if($is_bind['errorNum'] != 0){
    $bind = false;
}else{
    $bind = true;
}
$info = Info::find()->andWhere(['member_id'=>$user->id])->one();
?>
<style>
    .zhszlist{
        padding: 0 5%;
        background: #fff;
        color: #3c3c3c;
    }
    .f13 {
        font-size: 1.3rem;
    }
</style>
<div class="wapper bgd-f5f5f4">
<!--content-->
<div class="content">
    <br>
    <div class="cy-zhsz">
    <div style="border-top: 1px solid #c8c8c8"></div>
        <div class="zhszlist">
            <ul>
                <li>
                        <span class="f13">
                            <img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-zhse1.png" alt=""  width="4%" class="middle" style="margin-right: 3%; padding:0 1%;"/>手机号
                        </span>
                    <span class="zhsright" style="background: none;padding-right: 0"><?= mb_substr($user->username,0,3).'*******'. mb_substr($user->username,7,4,'utf-8')?></span>
                </li>
                <li>
                        <span class="f13">
                            <img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-zhse2.png" alt=""   width="6%" class="middle" style="margin-right: 3%"/>实名认证
                        </span>
                    <span class="zhsright" style="<?php if($user->real_name){echo 'background:none;padding-right: 0';} ?>">
                        <?php if($user->real_name){ ?>
                            <?= '*'.mb_substr($user->real_name,1,8,'utf-8'); ?>
                        <?php }else{ ?>
                            <a href="<?php echo Url::to(['site/step2']);?>">去认证</a>
                        <?php } ?>
                    </span>
                </li>
                <li>
                        <span class="f13">
                            <img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-zhse3.png" alt=""   width="6%" class="middle" style="margin-right: 3%"/>身份证
                        </span>
                    <span class="zhsright" style="<?php if($user->idcard){echo 'background:none;padding-right: 0';} ?>">
                        <?php if($user->idcard){ ?>
                            <?php $length=mb_strlen($user->idcard, 'utf-8'); if($length == 15) {$end = mb_substr($user->idcard, 11,4, 'utf-8') ; }else{$end = mb_substr($user->idcard, 14,4, 'utf-8');} echo mb_substr($user->idcard, 0,6, 'utf-8') .'********'.$end; ?>
                        <?php }else{ ?>
                            <a href="<?php echo Url::to(['site/step2']);?>">去认证</a>
                        <?php } ?>
                    </span>
                </li>
                <li>
                        <span class="f13">
                            <img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-zhse4.png" alt=""  width="6%" class="middle" style="margin-right: 3%"/>银行卡
                        </span>
                    <span class="zhsright" style="<?php if($bind){echo 'background:none;padding-right: 0';} ?>">
                        <?php if($bind){ ?>
                           <a href="<?php echo Url::to(['bankcardlist/index']);?>"> <?php $length = mb_strlen($info->bank_card, 'utf-8');   echo mb_substr($info->bank_card, 0, 4, 'utf-8'). '***********'.mb_substr($info->bank_card, $length-4, 4, 'utf-8') ;?></a>
                        <?php }else{ ?>
                            <a href="<?php echo Url::to(['site/bindcard']);?>">去认证</a>
                        <?php } ?>

                    </span>
                </li>
            </ul>
        </div>
        <div style="border-top: 1px solid #c8c8c8"></div>
        <br>
        <div style="border-top: 1px solid #c8c8c8"></div>
        <div class="zhszlist">
            <ul>
                <li>
                    <a style="display:block;" href="<?php echo Url::to(['member/repass']);?>">
                        <span class="f13">
                            <img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-zhse5.png" alt=""  width="5.5%" class="middle" style="margin-right: 3%"/>修改登录密码
                        </span>
                        <span class="zhsright"></span>
                    </a>
                </li>
            </ul>
        </div>

        <div style="border-top: 1px solid #c8c8c8"></div>
        <br>
        <div style="border-top: 1px solid #c8c8c8"></div>
        <div class="zhszlist">
            <ul>
                <a href="<?php echo Url::to(['site/contact']);?>"><li>
                        <span class="f13">
                            <img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-zhse8.png" alt=""  width="6%" class="middle" style="margin-right: 3%"/>联系我们
                        </span>
                    <span class="zhsright"></span>
                </li>
                </a>
            </ul>
        </div>
        <div style="border-top: 1px solid #c8c8c8"></div>
        <br>
        <a href="<?php echo Url::to(['site/logout']);?>" class="border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10 f16" style="display:block;text-align:center;width: 90%;  -webkit-border-radius: 3px;
    -moz-border-radius: 3px;border-radius: 3px;margin:0 auto;">退出当前帐号</a>

    </div>
    <br><br>


</div>
<!--content end-->
</div>