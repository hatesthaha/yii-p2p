<?php

namespace common\models\base\activity;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "hold_activity".
 *
 * @property integer $id
 * @property string $activity_name
 * @property integer $activity_begin
 * @property integer $activity_end
 * @property double $gold_money
 * @property double $activity_rate
 * @property integer $gold_day
 * @property double $red_money
 * @property integer $red_bothway
 * @property string $red_money_rang
 * @property integer $create_at
 * @property integer $update_at
 */
class HoldActivity extends ActiveRecord
{
    //活动开启
    const STATUS_OPEN = 1;
    //活动过期或者删除
    const STATUS_CLOSS = -1;

    //双向奖励开启
    const RED_BOTHWAY_YES = 1;
    //单独奖励推荐者
    const RED_BOTHWAY_TO_INVITER = 2;
    //单独奖励被推荐者
    const RED_BOTHWAY_TO_INVITEE = 3;


    private $_statusLabel;

    private $_red_bothway;
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
        return 'hold_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_begin', 'activity_end', 'gold_day', 'red_bothway','status', 'create_at', 'update_at'], 'integer'],
            [['gold_money', 'activity_rate'], 'number'],
            [['activity_name', 'red_money_rang','rid_list'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_name' => '活动名称',
            'activity_begin' => '活动开始时间',
            'activity_end' => '活动结束时间',
            'gold_money' => '体验金金额',
            'activity_rate' => '活动期间利率',
            'gold_day' => '体验金天数',
            'status' => '状态',
            'rid_list' => '活动规则对应列表',
            'red_bothway' => '红包奖励规则',
            'red_money_rang' => '红包金额概率',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::statusLabel();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }
    public static function statusLabel(){
        return [
            self::STATUS_OPEN=>"进行中",
            self::STATUS_CLOSS => "已结束或终止",
        ];
    }

    public function getStatusbothway()
    {
        if ($this->_red_bothway === null) {
            $statuses = self::statusbothwayLabel();
            $this->_red_bothway = $statuses[$this->red_bothway];
        }
        return $this->_red_bothway;
    }
    public static function statusbothwayLabel(){
        return [
            self::RED_BOTHWAY_YES => "双向红包",
            self::RED_BOTHWAY_TO_INVITER => "单独奖励推荐者",
            self::RED_BOTHWAY_TO_INVITEE => '单独奖励被推荐者',
        ];
    }


}
