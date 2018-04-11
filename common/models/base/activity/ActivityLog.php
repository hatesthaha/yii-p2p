<?php

namespace common\models\base\activity;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "activity_log".
 *
 * @property integer $id
 * @property string $phone
 * @property integer $invite_id
 * @property string $invite_phone
 * @property double $experience_money
 * @property double $red_packet
 * @property string $actibity_source
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class ActivityLog extends ActiveRecord
{   //活动完成
    const STATUS_SUCCESS = 1;
    //活动进行中
    const STATUS_ACTIVITY = 0;
    //活动过期或者删除
    const STATUS_ERROR = -1;

    //推荐人没有领取红包
    const STATUS_INVITER_DRAW_SUCC = 2;
    //推荐人领取了红包
    const STATUS_INVITER_DRAW_ERR = -2;

    //被推荐人没有领取红包
    const STATUS_INVITEE_DRAW_SUCC = 3;
    //被推荐人已经领取红包
    const STATUS_INVITEE_DRAW_ERR = -3;

    //双向奖励开启
    const RED_BOTHWAY_YES = 1;
    //单独奖励推荐者
    const RED_BOTHWAY_TO_INVITER = 2;
    //单独奖励被推荐者
    const RED_BOTHWAY_TO_INVITEE = 3;


    private $_statusLabel;

    private $_inviteeLabel;
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
        return 'activity_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invite_id', 'status', 'create_at', 'update_at','end_at','invitee_draw','inviter_draw','type'], 'integer'],
            [['experience_money', 'red_packet'], 'number'],
            [['phone', 'invite_phone', 'actibity_source'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => '被邀请手机号',
            'invite_id' => '邀请者id值',
            'invite_phone' => '邀请者手机号',
            'experience_money' => '赠送的体验金',
            'red_packet' => '红包',
            'actibity_source' => '活动来源',
            'status' => '记录状态',
            'type' => '红包类型',
            'invitee_draw' => '被邀请者领取',
            'inviter_draw' => '邀请者领取',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'end_at' => '结束时间'
        ];
    }
    public function getinviterLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::inviterLabel();
            $this->_statusLabel = $statuses[$this->inviter_draw];
        }
        return $this->_statusLabel;
    }
    public static function inviterLabel(){
        return [
            self::STATUS_INVITER_DRAW_SUCC=>"未领取",
            self::STATUS_INVITER_DRAW_ERR => "已领取",
        ];
    }
//被邀请者的状态显示
    public function getinviteeLabel()
    {
        if ($this->_inviteeLabel === null) {
            $statuses = self::inviteeLabel();
            $this->_inviteeLabel = $statuses[$this->invitee_draw];
        }
        return $this->_inviteeLabel;
    }
    public static function inviteeLabel(){
        return [
            self::STATUS_INVITEE_DRAW_SUCC=>"未领取",
            self::STATUS_INVITEE_DRAW_ERR => "已领取",
        ];
    }

}
