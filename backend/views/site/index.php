<?php
/* @var $this yii\web\View */
use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<div class="site-index">
    <?=Html::jsFile('@web/adminlte/js/jquery.min.js')?>
    <?=Html::jsFile('@web/js/backend.js')?>
<?php
$newuser = User::find()->where(['id'=>\App::$app->user->identity->getId()])->one();

if($newuser->role == 'admin'){
?>
    <div class="row" style="margin-bottom: 50px">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->

            <ul class="nav nav-tabs pull-right">

                <li class="pull-left header"><i class="fa fa-inbox"></i> 网站账户变动</li>
            </ul>
            <div class="tab-content no-padding" id="bian" style="font-size:20px; text-indent: 20px;margin-left: 10px;margin-top: 20px">


            </div>
            <div class="tab-content no-padding" style="font-size:20px; text-indent: 20px;margin-left: 10px;margin-top: 20px">
            <?= $ret_msg ?>

            </div>
        </div><!-- /.nav-tabs-custom -->
    </div>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>
                        <?= $order ?>
                    </h3>
                    <p>
                        今日投资数
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= Url::to(['order/index']) ?>" class="small-box-footer">
                    更多信息 <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>
                        <?= $payment ?>
                    </h3>
                    <p>
                        今日充值数
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?= Url::to(['sinadeposit/index']) ?>" class="small-box-footer">
                    更多信息 <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>
                        <?= $user ?>
                    </h3>
                    <p>
                        今日注册数
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="<?= Url::to(['ucentermember/index']) ?>" class="small-box-footer">
                    更多信息 <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>
                        <?= $withdraw ?>
                    </h3>
                    <p>
                        今日提现数
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="<?= Url::to(['sinawithdraw/index']) ?>" class="small-box-footer">
                    更多信息 <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
    </div><!-- /.row -->
    <?= Html::a(Yii::t('app', '导出'), ['export'], ['class' => 'btn btn-success']) ?>

  <?= Html::a(Yii::t('app', '导出用户统计数据'), ['exportuser'], ['class' => 'btn btn-success']) ?>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
			<th>时间</th>
            <th>充值金额 </th>
			<th>提现金额</th>
			<th>比率</th>
            <th>投资金额</th>
            <th>赎回金额</th>
			<th>比率</th>
            <th>在投收益</th>
            <th>体验金收益</th>
            <th>红包收益</th>
			<th>总收益</th>
			<th>创建时间</th>
            
        </tr>
        </thead>
        <tbody>
        <?php foreach($log as $item){ ?>
            <tr data-key="1">
				<td><?= date('Y-m-d',$item->t_date); ?></td>
                <td><?= $item->t_recharge; ?></td>
				<td><?= $item->t_withdraw; ?></td>
                <?php if($item->t_recharge) { ?>
				    <td><?= (ceil($item->t_withdraw/$item->t_recharge*10000)/100).'%';?></td>
                <?php }else{ ?>
                    <td></td>
                <?php } ?>
                <td><?= $item->t_invest; ?></td>
                <td><?= $item->t_redeem; ?></td>
                <?php if($item->t_invest) { ?>
                    <td><?= (ceil($item->t_redeem/$item->t_invest*10000)/100).'%';?></td>
                <?php }else{ ?>
                    <td></td>
                <?php } ?>

                <td><?= $item->t_profit; ?></td>
                <td><?= $item->t_gold; ?></td>
                <td><?= $item->t_red; ?></td>
				<td><?= $item->t_profit + $item->t_gold + $item->t_red;?></td>
				<td><?= date('Y-m-d',$item->create_at); ?></td>
                
            </tr>
        <?php } ?>
       <tr> <td colspan="8"><?= LinkPager::widget(['pagination' => $pages]); ?></td></tr>
        </tbody>
    </table>
    <?php }else{?>
    <div class="row" >
        <div class="col-lg-3 col-xs-6">
            <?php foreach($product as $item){ ?>
                    <?=$item['title'] ?>已通过审核
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
