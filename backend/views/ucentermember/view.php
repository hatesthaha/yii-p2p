<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\UcenterMember;
use common\models\base\ucenter\Cat;
use common\models\base\ucenter\Catmiddle;
/* @var $this yii\web\View */
/* @var $model common\models\UcenterMember */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '用户信息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$cattype = Catmiddle::find()->where(['uid'=>$model->id])->asArray()->all();
$cat = [];
foreach($cattype as $k=>$v){
    $newname = Cat::find()->where(['id'=>$v['cid']])->asArray()->one();

    array_push($cat,$newname['name']);
}

?>
<div class="ucenter-member-view">


    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            'phone',
            'email:email',
            'idcard',
            'real_name',
            [
                'attribute' => 'status',
                'value' => $model->statusLabel,
            ],

            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            'create_ip',
            'create_area',
            'create_channel',
            'login_ip',

            'parent_member_id',

        ],
    ]) ?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>用户类型</th>
            <th><?php
                if($cat){
                    foreach($cat as $k=>$v){
                        echo $v.',';
                    }
                }else{
                    echo "未设置";
                }
                ?>
            </th>
        </tr>
        </thead>
    </table>
    <?php if($sinabank) {?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>新浪账户认证id</th>
            <th>新浪账户电话</th>
            <th>新浪账户省份</th>
            <th>新浪账户市</th>
            <th>新浪账户银行</th>
            <th>新浪账户银行卡</th>
            <th>新浪账户余额</th>
        </tr>
        </thead>
        <tbody>
            <tr data-key="1">
                <td><?= $sinabank['identity_id']; ?></td>
                <td><?= $sinabank['phone_no']; ?></td>
                <td><?= $sinabank['province']; ?></td>
                <td>
                    <?= $sinabank['city']; ?>
                </td>
                <td><?= $sinabank['bank_name']; ?></td>
                <td><?= $sinabank['bank_account_no']; ?></td>
                <td><?= $sinabank['sinamoney']; ?></td>
            </tr>
        </tbody>
    </table>
    <?php } ?>
</div>
