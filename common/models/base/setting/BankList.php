<?php

namespace common\models\base\setting;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bank_list".
 *
 * @property integer $id
 * @property string $bank_code
 * @property string $bank_name
 * @property string $card_type
 * @property string $card_attribute
 * @property double $binding_pay_1time_limit
 * @property double $binding_pay_time_limit
 * @property double $binding_pay_day_limit
 * @property double $binding_pay_time_min_limit
 * @property integer $is_valid
 * @property integer $create_at
 * @property integer $update_at
 */
class BankList extends ActiveRecord
{
    //是否可用--可用
    const IS_VALID_TRUE = 1;
    //是否可用--不可用
    const IS_VALID_FALSE = 0;

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
        return 'bank_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_name', 'bank_code', 'binding_pay_1time_limit', 'binding_pay_time_limit', 'binding_pay_day_limit', 'binding_pay_time_min_limit','service_tel'], 'required'],
            [['binding_pay_1time_limit', 'binding_pay_time_limit', 'binding_pay_day_limit', 'binding_pay_time_min_limit'], 'number'],
            [['is_valid', 'create_at', 'update_at'], 'integer'],
            [['bank_code', 'card_type', 'card_attribute'], 'string', 'max' => 10],
            [['bank_name','service_tel'], 'string', 'max' => 128],
            [['bank_logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'bank_name' => '银行名称',
            'bank_code' => '银行编号',
            'service_tel' => '银行客服电话',
            'bank_logo' => '银行logo',
            'card_type' => '卡类型',
            'card_attribute' => '卡属性',
            'binding_pay_1time_limit' => '首次绑卡充值上限',
            'binding_pay_time_limit' => '已绑卡充值上限',
            'binding_pay_day_limit' => '每日限额',
            'binding_pay_time_min_limit' => '单笔最低充值限额',
            'is_valid' => '是否有效',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     * @return BankListQuery the active query used by this AR class.
     */
//    public static function find()
//    {
//        return new BankListQuery(get_called_class());
//    }

    public function isValidLables()
    {
        return [
            self::IS_VALID_TRUE => "是",
            self::IS_VALID_FALSE => "否",
        ];
    }
}
