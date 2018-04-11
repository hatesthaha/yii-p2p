<?php

namespace common\models\base\fund;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\UcenterMember;
/**
 * This is the model class for table "fund_thirdproduct".
 *
 * @property integer $id
 * @property string $title
 * @property string $intro
 * @property string $source
 * @property string $creditor
 * @property string $contract
 * @property string $intentcontract
 * @property string $intentrealname
 * @property string $realname
 * @property string $remarks
 * @property double $amount
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $intent
 * @property double $rate
 * @property integer $invest_people
 * @property integer $invest_sum
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $check_user_id
 * @property string $reject
 * @property UcenterUser $createUser
 * @property UcenterUser $checkUser
 */
class Thirdproduct extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_REGECT = 3;

    //意向债权
    const INTENT_YI =1;
    //可购债权
    const INTENT_CHECK =2;
    private $_statusLabel;
    private $_intentLabel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_thirdproduct';
    }
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
    public function rules()
    {
        return [
            [['title', 'intro', 'source', 'creditor',  'remarks', 'amount', 'start_at', 'end_at', 'rate'], 'required'],
           // [['amount', 'rate'], 'number'],
//            [['start_at', 'end_at', 'invest_people', 'invest_sum', 'create_at', 'update_at', 'status', 'create_user_id', 'check_user_id'], 'integer'],
//            [['title', 'intro', 'source', 'creditor',  'remarks'], 'string', 'max' => 255],
            //[['contract'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf,doc'],
            //[['contract'], 'file', 'maxFiles' => 10,'skipOnEmpty' => false],
            //[['intentcontract'], 'file', 'maxFiles' => 10,'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'intro' => '简介',
            'source' => '投资来源',
            'creditor' => '债权人',
            'maxcreditor' => '最大债权人',
            'contract' => '债权合同',
            'realname' => '上传合同文件',
            'intentcontract' => '意向合同',
            'intentrealname' => '上传意向文件',
            'remarks' => '备注',
            'amount' => '项目总额',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'rate' => '利率',
            'reject' => '驳回理由',
            'intent' => '意向',
            'invest_people' => '已经投资的人数',
            'invest_sum' => '已经投资的总额',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'status' => '状态',
            'create_user_id' => '创建人ID',
            'check_user_id' => '审核人',
            'borrowman' =>'借贷人'
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
            self::STATUS_ACTIVE => "已审核",
            self::STATUS_INACTIVE => "未审核",
            self::STATUS_REGECT =>'驳回',
        ];
    }
    public function getIntentLabel()
    {
        if ($this->_intentLabel === null) {
            $statuses = self::yilabels();
            $this->_intentLabel = $statuses[$this->intent];
        }
        return $this->_intentLabel;
    }
    public static function yilabels()
    {
        return [
            self::INTENT_YI => "意向债权",
            self::INTENT_CHECK => "已购债权",

        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(UcenterUser::className(), ['id' => 'create_user_id']);
    }
    public function getTypeocUser()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'creditor']);
    }
    public function getTypemaxUser()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'maxcreditor']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckUser()
    {
        return $this->hasOne(User::className(), ['id' => 'check_user_id']);
    }
}
