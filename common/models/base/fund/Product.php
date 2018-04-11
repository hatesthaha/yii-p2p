<?php

namespace common\models\base\fund;

use Yii;
use common\models\User;
use common\models\UcenterMember;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "fund_product".
 *
 * @property integer $id
 * @property string $title
 * @property string $intro
 * @property double $amount
 * @property integer $start_at
 * @property integer $end_at
 * @property double $rate
 * @property integer $invest_people
 * @property integer $invest_sum
 * @property string $each_max
 * @property string $each_min
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $status
 * @property integer $type
 * @property integer $create_user_id
 * @property integer $check_user_id
 * @property integer $ocreditor
 * @property FundOrders[] $fundOrders
 * @property UcenterUser $createUser
 * @property UcenterUser $checkUser
 */
class Product extends ActiveRecord
{
	public $temp_products;
    public $newstatus;
    //锁定状态
    const STATUS_LOCK = 2;
    //未锁定状态
    const STATUS_UNLOCK = 0;
    //项目售罄
    const STATUS_OUT = 1;
    const STATUS_DELETE = -1;

    //债权项目
    const TYPE_PRO = 0;
    //债权转让项目
    const TYPE_THIRD = 1;

    private $_statusLabel;
    private $typeLabel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_product';
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
            [['title', 'intro', 'start_at', 'rate','maxcreditor','ocreditor'], 'required'],

            //[[ 'rate'], 'number'],
          //  [['invest_people', 'create_at', 'update_at', 'status', 'create_user_id', 'check_user_id'], 'integer'],
//            [['amount','invest_sum'], 'double'],
//            [['title', 'intro'], 'string', 'max' => 255],
//            [['each_max', 'each_min'], 'string', 'max' => 1000],
//        	['each_max','validate_max']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '投资标题',
            'intro' => '投资简介',
            'amount' => '项目总额',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'rate' => '利率',
            'virtual_invest_people' => '虚拟增加人数',
            'virtual_amonnt' => '虚拟增加钱数',
            'invest_people' => '已经投资的人数',
            'invest_sum' => '已经投资的总额',
            'each_max' => '每次最高投资',
            'each_min' => '每次最低投资',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'status' => '投资状态',
        	'contract' => '合同文件',
        	'temp_products' => '第三方投资项目',
            'create_user_id' => '创建人',
            'check_user_id' => '审核人',
            'newstatus'=> '上线状态',
            'type' => '项目类型',
            'ocreditor' => '原始债权人',
            'maxcreditor' => '最大债权人',
        ];
    }


    /**
     * @inheritdoc
     */
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }
    public function getTypeLabel()
    {
        if ($this->typeLabel === null) {
            $statuses = self::getArrayTypes();
            $this->typeLabel = $statuses[$this->type];
        }
        return $this->typeLabel;
    }
    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [
            self::STATUS_LOCK => Yii::t('app', '锁定'),
            self::STATUS_UNLOCK => Yii::t('app', '未锁定'),
            self::STATUS_OUT => Yii::t('app', '项目售罄'),
            self::STATUS_DELETE => Yii::t('app', '删除项目'),
        ];
    }
    public static function getArrayTypes()
    {
        return [
            self::TYPE_PRO => Yii::t('app', '债权项目'),
            self::TYPE_THIRD => Yii::t('app', '债权转让项目'),
        ];
    }
    //验证最大投资金额
    public function validate_max($attribute, $params)
    {
    	$this->addError($attribute,Yii::t('app', '不能大于'));
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFundOrders()
    {
        return $this->hasMany(FundOrders::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'create_user_id']);
    }
    public function getTypeocUser()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'ocreditor']);
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
        return $this->hasOne(UcenterUser::className(), ['id' => 'check_user_id']);
    }
    
   
}
