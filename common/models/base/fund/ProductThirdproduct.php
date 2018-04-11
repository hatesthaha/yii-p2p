<?php

namespace common\models\base\fund;

use Yii;
use yii\behaviors\TimestampBehavior;
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
 * @property double $rate
 * @property integer $invest_people
 * @property integer $invest_sum
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $check_user_id
 *
 * @property UcenterUser $createUser
 * @property UcenterUser $checkUser
 */
class ProductThirdproduct extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    private $_statusLabel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_product_thirdproduct';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
                'updatedAtAttribute' => 'update_at',
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['title', 'intro', 'source', 'creditor',  'remarks', 'amount', 'start_at', 'end_at', 'rate', 'invest_people', 'invest_sum', 'create_at', 'update_at'], 'required'],
           // [['amount', 'rate'], 'number'],
//            [['start_at', 'end_at', 'invest_people', 'invest_sum', 'create_at', 'update_at', 'status', 'create_user_id', 'check_user_id'], 'integer'],
//            [['title', 'intro', 'source', 'creditor',  'remarks'], 'string', 'max' => 255],
            //[['contract'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf,doc'],
            [['contract'], 'file', 'maxFiles' => 10],
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
            'contract' => '债权合同',
            'realname' => '上传合同文件',
            'intentcontract' => '意向合同',
            'intentrealname' => '上传意向文件',
            'remarks' => '备注',
            'amount' => '项目总额',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'rate' => '利率',
            'invest_people' => '已经投资的人数',
            'invest_sum' => '已经投资的总额',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'status' => '状态',
            'create_user_id' => '创建人ID',
            'check_user_id' => '审核人ID',
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
            self::STATUS_DELETED => "删除",
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(UcenterUser::className(), ['id' => 'create_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckUser()
    {
        return $this->hasOne(UcenterUser::className(), ['id' => 'check_user_id']);
    }
}
