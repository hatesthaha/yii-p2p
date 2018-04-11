
<div class="shadowBox m-b1 page-con5">
               <p class="page-con51">欢迎您，
               <span>
                <?php 
                    if(yii::$app->user->identity->real_name)
                    {
                	   echo yii::$app->user->identity->real_name;
                    }
                    else 
                    {
                    	echo yii::$app->user->identity->username;
                    }
                ?>
               </span></p> 
                <ul class="page-con52 clearFloat">
                    <li class="page-con521">
                        <p>上次登陆：<span class="fon-c4"><?php echo date("Y-m-d H:i:s",$infos_rar['updated_at']); ?></span></p>
                    </li>
                    <li class="page-con522">
                        <p>账户可用余额：<span class="red-color"><?php if(isset($infos_rar['balance'])){ echo number_format($infos_rar['balance'],2); } else {echo 0;}?>元</span></p>
                    </li>
                    <li class="page-con523 clearFloat">
                        <a href="<?= yii\helpers\Url::to(['money/recharge']); ?>">充值</a>
                        <a href="<?= yii\helpers\Url::to(['money/withdraw']); ?>">提现</a>
                    </li>
                </ul>
            </div>