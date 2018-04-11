<?php
use yii\base\View;
use yii\widgets\LinkPager;

$this->title = '邀请注册';
?>
<div class="main aqbz">
    <div class="left" id="left">
        <div class="page-con4">
            <p class="page-con41"><a href="<?php echo yii\helpers\Url::to(['setting/setting']); ?>"><img width="100px"
                                                                                                         height="100px"
                                                                                                         src="<?php echo yii::$app->homeUrl; ?>upload/<?php echo $model["person_face"]; ?>"
                                                                                                         alt="头像"/></a>
            </p>

            <?php if (yii::$app->user->identity->real_name) { ?>
                <p><?php echo yii::$app->user->identity->real_name; ?></p>
            <?php } else { ?>
                <p><?php echo yii::$app->user->identity->username; ?></p>
            <?php } ?>
        </div>
        <ul class="Personal">
            <li class="PersonalT">账户信息</li>
            <li class="Personal1"><a href="<?= yii\helpers\Url::to(['account/overview']); ?>">账户概况</a></li>
            <li class="Personal2"><a href="<?= yii\helpers\Url::to(['money/recharge']); ?>">充值提现</a></li>
            <li class="Personal3"><a href="<?= yii\helpers\Url::to(['sign/index']); ?>">我的签到</a></li>
            <li class="Personal4"><a href="<?= yii\helpers\Url::to(['setting/setting']); ?>">个人设置</a></li>
            <li class="Personal5 hover"><a href="<?= yii\helpers\Url::to(['invitation/invitation']); ?>">邀请注册</a></li>
            <!--  <li class="Personal6"><a href="<?= yii\helpers\Url::to(['law/law']); ?>">法律服务</a></li>  -->
        </ul>
    </div>
    <div class="right" id="right" style="background:#f6f6f6; border:none;">
        <?= View::render("@www/views/layouts/ucenter.php", ['infos_rar' => $infos_rar]); ?>
        <div class="shadowBox page-con6">
            <p class="spage-con16T"><span>邀请注册</span></p>

            <div class="spage-con17">
                <p class="spage-con132T">* 邀请注册</p>

                <form action="#">
                    <table class="spage-con17con">
                        <tr>
                            <td class="tr">邀请链接 :</td>
                            <td><input id="invitation_url" class="spage-con17input" type="text"
                                       value="<?php echo yii::$app->urlManager->hostInfo . yii\helpers\Url::to(['site/signup', 'code' => $invitation_code]); ?>"/><a
                                    class="spage-con17btn" href="javascript:void(0);"
                                    onclick="return copyUrl();">复制链接</a></td>
                        </tr>
                        <tr>
                            <td class="tr">邀请码 :</td>
                            <td class="spage-con17num"><?php echo $invitation_code; ?></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="spage-con13">
                <div class="spage-con132 spage-con19">
                    <p class="spage-con132T">* 邀请记录</p>
                    <table class="spage-con132table Color-change">
                        <tr>
                            <th>被邀请人</th>
                            <th>手机号码</th>
                            <th>被邀请时间</th>
                        </tr>
                        <?php if ($invitation_log) {
                            foreach ($invitation_log as $K => $V) { ?>
                                <tr>
                                    <td><?php for ($n = 0; $n < mb_strlen($V['real_name'], 'utf-8') - 1; $n++) {
                                            echo '* ';
                                        }
                                        echo mb_substr($V['real_name'], mb_strlen($V['real_name'], 'utf-8') - 1, 1, 'utf-8'); ?></td>
                                    <td><?php echo substr($V['phone'], 0, 3) . '*****' . substr($V['phone'], 8, 3); ?></td>
                                    <td><?php echo date("Y-m-d H:i:s", $V['created_at']) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr class="spage-con163tacon">
                                <td colspan="3" style="padding-top:30px;color:gray;font-size:14px;">暂无邀请记录</td>
                            </tr>
                        <?php } ?>
                    </table>
                    <?php
                    if ($invitation_log) {
                        echo LinkPager::widget([
                            'pagination' => $pages,
                            'maxButtonCount' => 5
                        ]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<script src="<?php echo Yii::$app->homeUrl; ?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
    function copyUrl() {
        var Url2 = document.getElementById("invitation_url");
        Url2.select(); // 选择对象
        document.execCommand("Copy"); // 执行浏览器复制命令
        alert("已复制好，可贴粘。");
    }
</script>