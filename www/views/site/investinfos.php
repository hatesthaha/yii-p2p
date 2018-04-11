<?php

use yii\widgets\LinkPager;
use common\models\fund\Order;
use yii\bootstrap\ActiveForm;

\www\assets\CircleAppAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');

$this->title = $model['title'];
?>
<script src="<?php echo Yii::$app->homeUrl; ?>myAssetsLib/js/jquery-1.7.1.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $(".xj-wanh01 .perv-next > li.prev span").html("<i class='icon-caret-left'></i>");
        $(".xj-wanh01 .perv-next > li.prev a").html("<i class='icon-caret-left'></i>");
        $(".xj-wanh01 .perv-next > li.next span").html("<i class='icon-caret-right'></i>");
        $(".xj-wanh01 .perv-next > li.next a").html("<i class='icon-caret-right'></i>");

    });
</script>
<script>
    var balance = <?php echo isset($model_asset->balance)?$model_asset->balance:0; ?>;
    var amount = <?php echo isset($model->amount)?$model->amount:0; ?>;
    var invest_sum = <?php echo isset($model->invest_sum)?$model->invest_sum:0; ?>;
    var min = <?php echo $invest_min; ?>;
    var max = <?php echo $invest_max; ?>;
    var times = <?php echo $invest_times; ?>;
    var today_num = <?php echo $today_num; ?>;
    var reg = /^[1-9]+[.]?[0-9]*$/;
    $(document).ready(function () {
        $(".page-con211").change(function () {
            var money = $(".page-con211").val();
            $("#invest_total").text(money);
        });
        $('.page-con211').keypress(function(e){
            if(e.keyCode==13){
                e.preventDefault();
            }
        });
        $(".clearFloat a").click(function () {
            if ($(".page-con211").val() == '') {
                $(".page-con214").text("请输入申购金额");
            }
            else if (today_num >= times) {
                $(".page-con214").text('今日购买次数不能超过' + times + "次");
            }
            else if (!reg.test($(".page-con211").val())) {
                $(".page-con214").text("金额格式不正确");
            }
            else if (parseInt($(".page-con211").val()) > balance) {
                $("#invest_total").text($(".page-con211").val());
                $(".page-con214").text("账户余额不足");
            }/*
             else if(amount - invest_sum < min && $(".page-con211").val()!= min)
             {
             $(".page-con214").text("所投金额应为" + (amount - invest_sum));
             } */
            else if (parseInt($(".page-con211").val()) < min || parseInt($(".page-con211").val()) > max) {
                $(".page-con214").text("单笔限额：" + min + " ~ " + max + "元");
            }
            else if (parseInt($(".page-con211").val()) < min) {
                $(".page-con214").text("金额不能低于" + min + "元");
            }
            else if (parseInt($(".page-con211").val()) > max) {
                $(".page-con214").text("金额不能高于" + max + "元");
            }
            else {
                $("#invest_total").text($(".page-con211").val());
                if (confirm('请确认是否购买？')) {
                    $.post(
                        '<?php echo yii\helpers\Url::to(['site/buy']);?>',
                        {
                            'pid': "<?php echo $model->id; ?>",
                            'money': $(".page-con211").val(),
                            '_csrf': "<?php echo Yii::$app->request->getCsrfToken(); ?>"
                        },
                        function (data) {
                            alert(data);
                            if (data == '购买成功') {
                                window.location.href = window.location.href;
                            }
                            $("#balance").text(balance * 1 - $(".page-con211").val() * 1);
                        }
                    )
                }
            }

        });

    });

</script>
<script type="text/javascript">
    $(document).ready(function () {


        circle();
    });
    /*圆形百分比*/
    function circle() {
        var paper = null;

        function init(b, n, t, c, m) {
            //初始化Raphael画布
            this.paper = Raphael(b, 98, 98);

            //把底图先画上去
            this.paper.image("<?php echo $directoryAsset;?>/images/progressBg.png", 0, 0, 98, 98);

            //进度比例，0到1，在本例中我们画65%
            //需要注意，下面的算法不支持画100%，要按99.99%来画
            var percent = n,
                drawPercent = percent >= 1 ? 0.9999 : percent;

            //开始计算各点的位置，见后图
            //r1是内圆半径，r2是外圆半径
            var r1 = 42.5, r2 = 48, PI = Math.PI,
                p1 = {
                    x: 48,
                    y: 96
                },
                p4 = {
                    x: p1.x,
                    y: p1.y - r2 + r1
                },
                p2 = {
                    x: p1.x + r2 * Math.sin(2 * PI * (1 - drawPercent)),
                    y: p1.y - r2 + r2 * Math.cos(2 * PI * (1 - drawPercent))
                },
                p3 = {
                    x: p4.x + r1 * Math.sin(2 * PI * (1 - drawPercent)),
                    y: p4.y - r1 + r1 * Math.cos(2 * PI * (1 - drawPercent))
                },
                path = [
                    'M', p1.x, ' ', p1.y,
                    'A', r2, ' ', r2, ' 0 ', percent > 0.5 ? 1 : 0, ' 1 ', p2.x, ' ', p2.y,
                    'L', p3.x, ' ', p3.y,
                    'A', r1, ' ', r1, ' 0 ', percent > 0.5 ? 1 : 0, ' 0 ', p4.x, ' ', p4.y,
                    'Z'
                ].join('');

            //用path方法画图形，由两段圆弧和两条直线组成，画弧线的算法见后
            this.paper.path(path)
                //填充渐变色，从#3f0b3f到#ff66ff
                .attr({"stroke-width": 0.5, "stroke": "#d2d4d8", "fill": "90-" + c});

            //显示进度文字
            var timestamp = Date.parse(new Date()) / 1000;
            if (Math.round(percent * 100) == 100 || (timestamp - m) > 0) {
                $(t).text("售罄");
            }
            else
                $(t).text(Math.round(percent * 100) + "%");
        }

        init('bg<?= $model['id']?>', <?php if(($model['end_at'] - strtotime("now")) < 0) {echo 1;} else {echo 1 - ($model['amount']-$model['invest_sum'])/$model['amount'];}?>, '#txt<?= $model['id']?>', '#3598db', '<?php echo $model['end_at']; ?>');

    }
</script>
<script>
    $(document).ready(function () {
        var catoFram = $(".single-page-navcon");
        var subNav = $(".single-page-navtab li");
        catoFram[0].style.display = "block";
        subNav[0].className += " cur-on1";
        subNav.click(function (event) {
            event.preventDefault();
            var _this = $(this);
            var id = _this.data("cato");
            var cur = $("#" + id);
            subNav.removeClass("cur-on1");
            _this.addClass("cur-on1");
            catoFram.hide();
            cur.scrollTop(0);
            cur.show();
        });
    });
</script>
<div class="main page-con clearFloat">
    <div class="left w900">
        <div class="shadowBox m-b4 Shadow-add">
            <div class="page-cont">
                <div class="jdt spage-con201 left">
                    <div class="percentBox">
                        <div id="bg<?= $model['id'] ?>"></div>
                        <div id="bg<?= $model['id']; ?>"></div>
                        <div id="txt<?= $model['id']; ?>"
                             class="pertxt"><?= ceil(($model['amount'] - $model['invest_sum']) / $model['amount'] * 100); ?>
                            %
                        </div>
                    </div>
                    <?php if (($model['invest_sum'] >= $model['amount']) || ($model['end_at'] < strtotime("now"))) { ?>
                        <p>已抢光</p>
                    <?php } else { ?>
                        <p>投资进度</p>
                    <?php } ?>
                </div>
                <div class="ggwz spage-con202 left">
                    <div class="spage-con202con">
                        <p>起投金额：<?= $model['each_min']; ?>元起</p>

                        <p>保障方式： 本息保护</p>

                        <p>锁定期限：可随时转让变现</p>

                        <p>投资人数：<?= $model['invest_people']; ?>人</p>
                    </div>
                </div>
                <div class="ggjd spage-con203 left">
                    <p class="ft14">预计年化收益率</p>

                    <p class="ft28"><?php echo round($model['rate'], 3) * 100; ?>%+</p>

                    <p>投资1万元每天收益<?php echo round(($model['rate'] * 10000) / 365, 2); ?>元</p>
                </div>
                <div class="ggjd spage-con204 left">
                    <p class="ft14">本期项目金额</p>

                    <p class="ft-blue28"><?php echo $model['amount'] / 10000; ?>万</p>

                    <p><a class="zzjx" href=""></a></p>
                </div>
                <div class="clear"></div>
                <?php $now_time = strtotime("now");
                if ($model['invest_sum'] >= $model['amount']) { ?>
                    <p class="ljtz1"><a href="javascript:alert('已抢光，下次记得手要快哦~');">已抢光</a></p>
                <?php } elseif ($model['end_at'] < $now_time) { ?>
                    <p class="ljtz1"><a href="javascript:alert('已过期，下次记得手要快哦~');">已抢光</a></p>
                <?php } elseif ($model['start_at'] > $now_time) { ?>
                    <p class="ljtz1"><a href="javascript:alert('稍等片刻，即将开售~');">已抢光</a></p>
                <?php } ?>
            </div>
            <?php if($model['start_at'] <= $now_time && $model['end_at'] > $now_time && $model['invest_sum'] < $model['amount']) {?>
            <div>
            <div class="detail_title">
                <h1>确认申购</h1>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'invest-form',
                'action' => yii\helpers\Url::to(['site/buy']),
                'fieldConfig' => [
                    'template' => "<p><span class=\"red-color\">请输入申购金额：</span>{input}<a class=\"page-con212\" href=\"javascript:void(0)\">元</a></p>",
                ]
            ]); ?>
            <div class="page-con2">
                <div class="page-con21 clearFloat">
                    <div class="left">
                        <p class="page-con213 fon-14"><span class="c74">当前账户可用余额：</span><span id="balance" class="red-color">
                          <?php if (isset($model_asset->balance)) {
                                  echo round($model_asset->balance, 2);
                                } else {
                                  echo 0.00;
                                } ?>元</span></p>
                         <?= $form->field($model_asset, 'money')->textInput(['class' => 'page-con211', 'value' => '']); ?>
                        <p class="page-con214 red-color"></p>
                    </div>
	                    <p class="page-con22 right">
	                        <a href="<?php echo yii\helpers\Url::to(['money/recharge']) ?>">去充值</a>
	                    </p>
                </div>
                <div class="page-con3">
                    <p class="page-con31">购买总金额：<span id="invest_total">0</span> 元</p>

                    <p class="clearFloat"><a href="javascript:void(0)">立即购买</a></p>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <?php } ?>
        </div>
        <div class="shadowBox m-b4 Shadow-add">
            <div class="detail_title">
                <h1>多重保障</h1>
            </div>
            <div class="single-page-nav clearFloat">
                <ul class="single-page-navtab left">
                    <li data-cato="cfhk">
                        <span>充分还款保证</span>
                    </li>
                    <li data-cato="ffzbj">
                        <span>风险准备金</span>
                    </li>
                    <li data-cato="dsfzh">
                        <span>第三方账户托管</span>
                    </li>
                </ul>
                <div class="single-page-navcon right" id="cfhk">
                    <p>通过“理财王”所投资资产均为最高保证的资产类型。所有用户通过“理财王”的投资均不会进行中小企业借贷类资产投资。资产投向分别为:</p>

                    <p>1，央国企融资（绝对限定在国资委下属112家央企和各省省属大型国企）；</p>

                    <p>2，泛金融机构非标资产（包括有高质量增信措施的信托、基金和券商非标资产）；</p>

                    <p>3，车辆质押业务（完全控制车辆质权和实物资产存放）；</p>

                    <p>4，个小小额消费信贷（附加高额还款保证金及连带责任保证）；</p>

                    <p>5，证券融资业务（通过信托、基金、交易管制等模式实际控制资产风险）；</p>

                    <p>6，保险类（由保险公司承保的各类资产，一般包括保付代理、融资租赁等）；</p>

                    <p>
                        经过上述资产投向的资产保证后。理财王还分别设立多层级的保证金制度，保证资产兑付风险。个人消费信贷类、车辆质押类、证券融资类资产均分别有各种形式的高额保证金，该保证金处于“理财王”实际控制下，并在每项资产类目中予以实时展示相关余额。</p>

                    <p>另外，“理财王”还设立了面向平台全部用户的资产投资余额设立“风险准备金”制度作为最后一层的保障。</p>
                </div>
                <div class="single-page-navcon right" id="ffzbj">
                    <p>
                        风险准备金保障计划指当投资人受让的债权债务人出现逾期，且用于保证还款的“质押物、保证物、抵押物等”发生代偿风险的。“理财王”将从设立的风险保障金账户中的资金，先行偿付投资人对应该笔债权的本金以及相应的收益，从而为投资人营造一个安全的投资环境，保证投资人的本金安全。
                        “风险准备金账户”资金全部来源于“理财王”选取的民间借贷项目中的借款人（债务人）收取，并将收取的风险准备金存放入“风险准备金账户”并进行专户管理，由招商银行监管。</p>
                </div>
                <div class="single-page-navcon right" id="dsfzh">
                    <p>
                        第三方账户托管是指资金流运行在第三方账户托管公司，而不经过平台账户。从而避免了平台恶意挪用交易资金给投资人带来的风险。简单来说，就是平台动不了用户资金，跑路了也卷不了款。这也是银监提倡保障用户资金安全方面的有效手段。

                        为了最大确保全体理财人资金安全，理财王大胆地与新浪支付进行了合作，资金全程由新浪支付托管，投资和还款资金开设独立专用账户，实时专款专用，从而与理财王自有资金完全分离。同时有联合监管机构的实时监控，确保资金运转的合规透明。
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="right w290">
        <div class="shadowBox m-b4 Shadow-add">
            <h2 class="winning-title"><i class="icon-edit"></i>本期投资记录</h2>
            <table class="Winning">
                <tr>
                    <th>姓名</th>
                    <th>投资金额</th>
                    <th>投资时间</th>
                </tr>
                <?php if (isset($array_investlog)) {
                    foreach ($array_investlog as $K => $V) { ?>
                        <tr>
                            <td><?php for ($n=0;$n<mb_strlen($V['real_name'],'utf-8')-1;$n++){echo '* ';} echo mb_substr($V['real_name'], mb_strlen($V['real_name'],'utf-8')-1,1,'utf-8');?></td>
                            <td><?php if (isset($V['start_money'])) echo number_format($V['start_money'], 2); ?>元</td>
                            <td><?php if (isset($V['start_at'])) echo date("Y-m-d", $V['start_at']); ?></td>
                        </tr>
                    <?php }
                } ?>
                <?php if ($model['end_at'] < strtotime("now") && $model['invest_sum'] < $model['amount']) { ?>
                    <tr>
                        <td>* * 飞</td>
                        <td><?php echo number_format(($model['amount'] - $model['invest_sum']), 2); ?>元</td>
                        <td><?php if (isset($model['start_at'])) echo date("Y-m-d", $model['start_at']); ?></td>
                    </tr>
                <?php } ?>
            </table>
            <div class="xj-wanh01">
                <?php if (isset($array_investlog)) {
                    echo LinkPager::widget([
                        'pagination' => $pages,
                        'maxButtonCount' => 0,
                        'options' => ['class' => 'perv-next'],
                        'prevPageLabel' => '&lt',
                        'nextPageLabel' => '&gt',
                    ]);
                } ?>
            </div>

        </div>
    </div>
</div>
