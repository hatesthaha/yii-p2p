<?php
$this->title = '企业介绍';
?>


<div id="content">

    <div class="main gywm">
        <div class="left" id="left">
            <ul>
                <?php if ($left[0]['status'] == 1) { ?>
                    <li class="leib1 hover"><a
                            href="<?= yii\helpers\Url::to(['about/company']); ?>"><?= $left[0]['name'] ?></a></li>
                    <?php
                } ?>
                <?php if ($left[1]['status'] == 1) { ?>
                    <li class="leib2 "><a
                            href="<?= yii\helpers\Url::to(['about/media']); ?>"><?= $left[1]['name'] ?></a></li>
                    <?php
                } ?>
                <?php if ($left[2]['status'] == 1) { ?>
                    <li class="leib3"><a
                            href="<?= yii\helpers\Url::to(['about/partner']); ?>"><?= $left[2]['name'] ?></a></li>
                    <?php
                } ?>
                <?php if ($left[3]['status'] == 1) { ?>
                    <li class="leib4"><a href="<?= yii\helpers\Url::to(['about/news']); ?>"><?= $left[3]['name'] ?></a>
                    </li>
                    <?php
                } ?>
                <?php if ($left[4]['status'] == 1) { ?>
                    <li class="leib5"><a
                            href="<?= yii\helpers\Url::to(['about/guarantee']); ?>"><?= $left[4]['name'] ?></a></li>
                    <?php
                } ?>
                <?php if ($left[5]['status'] == 1) { ?>
                    <li class="leib6"><a href="<?= yii\helpers\Url::to(['about/join']); ?>"><?= $left[5]['name'] ?></a>
                    </li>
                    <?php
                } ?>
                <?php if ($left[6]['status'] == 1) { ?>
                    <li class="leib7"><a
                            href="<?= yii\helpers\Url::to(['about/contact']); ?>"><?= $left[6]['name'] ?></a></li>
                    <?php
                } ?>
                <?php if ($left[7]['status'] == 1) { ?>
                    <li class="leib8"><a href="<?= yii\helpers\Url::to(['help/index']); ?>"><?= $left[7]['name'] ?></a>
                    </li>
                    <?php
                } ?>

            </ul>
        </div>
        <div class="right" id="right">

            <h2><?php if (isset($infos)) {
                    echo $infos->title;
                } ?></h2>

            <div class="lxwm">
                <?php if (isset($infos)) {
                    echo $infos->content;
                } ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>

</div>