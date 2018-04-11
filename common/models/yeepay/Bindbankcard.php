<?php

namespace common\models\yeepay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "yeepay_bindbankcard".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $identityid
 * @property integer $identitytype
 * @property string $requestid
 * @property string $cardno
 * @property string $idcardtype
 * @property string $idcardno
 * @property string $username
 * @property string $phone
 * @property string $userip
 * @property integer $status
 * @property integer $from
 * @property string $error_msg
 * @property string $bankcode
 * @property string $card_name
 * @property integer $bindvalidthru
 * @property string $bindid
 * @property string $card_top
 * @property string $card_last
 * @property integer $create_at
 * @property integer $update_at
 */
class Bindbankcard extends ActiveRecord
{
    const STATUS_BIND = 1;
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yeepay_bindbankcard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'identityid', 'requestid', 'cardno', 'idcardno', 'username', 'phone', 'userip', 'error_msg'], 'required'],
            [['uid', 'identitytype', 'status', 'from', 'bindvalidthru', 'create_at', 'update_at'], 'integer'],
            [['identityid', 'requestid', 'cardno', 'idcardtype', 'idcardno', 'username', 'phone', 'userip', 'error_msg', 'bankcode', 'card_name', 'bindid', 'card_top', 'card_last'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户id',
            'identityid' => '用户标识',
            'identitytype' => '用户标识类型',
            'requestid' => '绑卡请求号',
            'cardno' => '银行卡号',
            'idcardtype' => '证件类型',
            'idcardno' => '证件号',
            'username' => '持卡人姓名',
            'phone' => '银行预留手机号',
            'userip' => '用户请求ip',
            'status' => '绑定状态',
            'from' => '操作来源（PC：1，ANDROID：2，IOS：3，WEIXIN：4）',
            'error_msg' => '错误信息',
            'bankcode' => '银行编码',
            'card_name' => '银行名称',
            'bindvalidthru' => '绑卡有效期',
            'bindid' => '绑卡ID',
            'card_top' => '卡号前 6 位',
            'card_last' => '卡号后 4 位',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
    public static function weekbind(){
        return $weekpeople = self::find()
            ->andWhere(['>=', 'create_at', time() - 7 * 24 * 3600])
            ->andWhere(['status'=>self::STATUS_BIND])
            ->count();
    }
}
