<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_member".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $idcard
 * @property string $user_ip
 * @property string $phone
 * @property integer $status
 * @property string $msg
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaMember extends ActiveRecord
{
    //用户删除信息
    const STATUS_DELETED = -1;
    //用户信息错误
    const STATUS_ERROR = 0;
    //用户完成绑定认证
    CONST STATUS_BINGING = 1;
    private $_statusLabel;
    //新浪余额
    public $sinamoney;
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
        return 'sina_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'create_at', 'update_at'], 'integer'],
            [['name', 'idcard', 'user_ip', 'phone'], 'string', 'max' => 25],
            [['msg','identity_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identity_id' => '用户标识信息',
            'uid' => '用户id',
            'name' => '用户名',
            'idcard' => 'Idcard',
            'user_ip' => '用户ip',
            'phone' => '认证手机号',
            'status' => '状态',
            'msg' => '备注信息',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }

    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::labels();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }
    public static function labels()
    {
        return [
            self::STATUS_DELETED => "删除",
            self::STATUS_ERROR => "信息错误",
            self::STATUS_BINGING =>'完成绑定认证',
        ];
    }
}
