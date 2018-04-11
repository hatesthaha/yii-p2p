<?php
use yii\base\View;
use yii\bootstrap\ActiveForm;

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
$this->title = '充值';
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
            <li class="Personal2 hover"><a href="<?= yii\helpers\Url::to(['money/recharge']); ?>">充值提现</a></li>
            <li class="Personal3"><a href="<?= yii\helpers\Url::to(['sign/index']); ?>">我的签到</a></li>
            <li class="Personal4"><a href="<?= yii\helpers\Url::to(['setting/setting']); ?>">个人设置</a></li>
            <li class="Personal5"><a href="<?= yii\helpers\Url::to(['invitation/invitation']); ?>">邀请注册</a></li>
            <!--  <li class="Personal6"><a href="<?= yii\helpers\Url::to(['law/law']); ?>">法律服务</a></li>  -->
        </ul>
    </div>
    <div class="right" id="right" style="background:#f6f6f6; border:none;">
        <?= View::render("@www/views/layouts/ucenter.php", ['infos_rar' => $infos_rar]); ?>
        <div class="shadowBox page-con6">
            <ul class="page-con6tab clearFloat">
                <li data-cato="bindcard">绑定银行卡</li>
                <li data-cato="recharge">充值</li>
                <li data-cato="redemption">赎回</li>
                <li data-cato="withdraw">提现</li>
            </ul>
            <div class="page-con6con" id="cz">
                <p class="xj-wan002">为保证充值成功，您的银行卡开户姓名必须为 <span><?php if ($result) {
                            echo $result['real_name'];
                        } ?></span></p>

                <form action="<?php echo yii\helpers\Url::to(['money/recharge']); ?>" method="post">
                    <input type="hidden" id="out_trade_no" value=""/>
                    <input type="hidden" id="ticket" value=""/>
                    <input type="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken(); ?>"/>

                    <div class="xjwanpage-con7 zhuce">
                        <div id="loading"
                             style="display: none;margin:auto 0;z-index:9999;position:relative; top:-10px; left:362px;">
                            <img width="23px" height="23px" alt=""
                                 src="<?php echo $directoryAsset; ?>/images/loading.gif" align="center"/></div>
                        <div class="xj-wan19 clearFloat">
                            <span class="xj-wan19L">银行卡：</span>

                            <div class="xj-wan19R clearFloat" style="width:548px;">
                                <?php if ($result_bind) { ?>
                                    <img class="bank-icon"
                                         src="<?php echo $directoryAsset; ?>/images/bank/<?php echo $logo_bind; ?>.png"
                                         alt=""/>
                                    <span
                                        class="bank-word"><?php echo $result_bind["bank_name"]; ?><?php echo substr($result_bind["bank_account_no"], 0, 4) . '************' . substr($result_bind["bank_account_no"], strlen($result_bind["bank_account_no"]) - 3, 3); ?></span>
                                    <span class="bank-info1">每日限充<?php echo $deposit_times; ?>次</span>
                                    <span class="bank-info3">限额：<?php echo $deposit_min ?> ~ <?php echo $deposit_max ?>
                                        元/次</span>
                                <?php } ?>
                            </div>
                        </div>
                        <p class="xj-wan9"><span>充值金额：</span>
                            <span class="text"><input type="text" id="money" name="money"
                                                      placeholder="请输入充值金额，且金额需为整数"/>元</span>
                        </p>

                        <p class="xj-wan9 label-tdr" style="width:546px;"><span>验证码：</span>
                            <input type="text" id="code" name="code" style="margin-left: 0px;"/><a class="yellow-btn"
                                                                                                   id="codea">获取验证码</a>
                        </p>

                        <a class="check-btn" colspan="2"><input type="button" value="立即充值" id="recharge"/></a>
                    </div>
                    <div id="formtext"></div>
                </form>

            </div>

        </div>
    </div>
    <div class="clear"></div>
</div>
<script src="<?php echo Yii::$app->homeUrl; ?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script>
    $(document).ready(function () {
        var catoFram = $(".page-con6con");
        var subNav = $(".page-con6tab li");
        catoFram[0].style.display = "block";
        subNav[1].className += " cur-on";
        subNav.click(function (event) {
            event.preventDefault();
            var _this = $(this);
            var id = _this.data("cato");
            location.href = "<?php echo yii\helpers\Url::to(['money/" + id + "'])?>";
        });
        $(".page-con102 tr:even").addClass("trOdd");
    });
</script>

<script>
    var wait = 0;
    var _wait = 0;
    function time() {
        if (wait == 0) {
            $('#codea').attr('class', 'yellow-btn');
            $('#codea').html('获取验证码');

        } else {
            setTimeout(function () {
                $('#codea').html(wait);
            });
            wait--;
            setTimeout(function () {
                time();

            }, 1000);
        }
    }
    function _time() {
        if (_wait != 0) {
            _wait--;
            setTimeout(function () {
                _time();
            }, 1000);
        }
    }

    $("#codea").click(function () {
        if (wait != 0) {
            return false;
        }
        var reg = /^[0-9]+$/;
        var money = $("#money").val();
        var min = <?php echo $deposit_min; ?>;
        var max = <?php echo $deposit_max; ?>;
        var times = <?php echo $deposit_times; ?>;
        var today_num = <?php echo $today_num; ?>;
        if (money == '') {
            alert("请输入充值金额");
        }
        else if(today_num >= times)
        {
			alert('今日充值次数不能超过'+times+'次');
        }
        else if (parseInt(money) < min || parseInt(money) > max) {
            alert("单笔限额：" + min + " ~ " + max + '元');
        }
        else if (reg.test(money)) {
            wait = 60;
            time();
            $('#codea').attr('class', 'hui-btn');
            $("#loading").css("display", "block");
            $.post("<?php echo yii\helpers\Url::to(['money/recharge']);?>", {
                'money': money,
                '_csrf': '<?php echo yii::$app->request->getCsrfToken();?>'
            }, function (data) {
                $("#loading").css("display", "none");
                var jsonobj = eval('(' + data + ')');
                if (jsonobj.errorNum == 0) {
                    $("#ticket").val(jsonobj.data.ticket);
                    $("#out_trade_no").val(jsonobj.data.out_trade_no);
                    alert("验证码发送成功，请查收短信");
                }
                else if (jsonobj.errorNum == 1) {
                    alert(jsonobj.errorMsg);
                    if (jsonobj.errorMsg == '请先进行绑定银行卡') {

                    }
                }
                else {
                    alert(data);
                }
            });

        }
        else {
            alert('充值金额必须为整数值');
        }
    });

</script>
<script>

    $(document).ready(function () {
        $("#recharge").click(function () {
            var code = $("#code").val();
            var ticket = $("#ticket").val();
            var out_trade_no = $("#out_trade_no").val();
            console.log(code);
            console.log(ticket);
            console.log(out_trade_no);
            if (code != '') {

//		          if(_wait == 0)
//		          {
//					_wait = 30;
//					_time();
//			      }
//		          else
//		          {
//					alert('为了保证安全，请等待'+_wait+'秒再次操作！');
//					return false;
//				  }
                $("#loading").css("display", "block");
                $.post("<?php echo yii\helpers\Url::to(['money/recharge']);?>",
                    {
                        'code': code,
                        'ticket': ticket,
                        'out_trade_no': out_trade_no,
                        '_csrf': '<?php echo yii::$app->request->getCsrfToken();?>'
                    },
                    function (data) {
                        $("#loading").css("display", "none");
                        alert(data);
                        if (data == '充值成功') {
                            location.href = "<?php echo yii\helpers\Url::to(['money/recharge']);?>";
                        }
                        else
                        {
                        	wait = 0;
                        }
                    }
                );

            }
            else if (code == '') {
                alert('请输入验证码');
            }
        });
    });
</script>
