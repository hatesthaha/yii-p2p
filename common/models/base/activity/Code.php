<?php

namespace common\models\base\activity;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\base\activity\Card;
/**
 * This is the model class for table "activity_code".
 *
 * @property integer $id
 * @property integer $coupon_id
 * @property string $name
 * @property string $validity_time
 * @property string $rate
 * @property string $use_end_time
 * @property string $use_at
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property integer $display
 */
class Code extends \yii\db\ActiveRecord
{
    //未兑换
    const STATUS_INACTIVE = 0;
    //已兑换
    const STATUS_USE = 1;
    const STATUS_DELETED = -1;


    //手动添加
    const DISPLAY_INACTIVE = 0;
    //自动添加
    const DISPLAY_USE = 1;
    private $_statusLabel;
    private $_displaysLabel;
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
//            [['status', 'display'], 'integer'],
         //   [['name', 'validity_time'], 'string', 'max' => 255],
           // [['rate', 'use_end_time', 'created_at', 'updated_at'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', '兑换码'),
            'validity_time' => Yii::t('app', '增息卡有效时长天数'),
            'rate' => Yii::t('app', '利率'),
            'coupon_id' => Yii::t('app', '选择增息卡种类'),
            'use_at' => Yii::t('app', '兑换码开始时间'),
            'use_end_time' => Yii::t('app', '兑换码结束时间'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'status' => Yii::t('app', '使用状态'),
            'display' => Yii::t('app', '添加方式'),
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
    public function getDisplaysLabel()
    {
        if ($this->_displaysLabel === null) {
            $displays = self::displays();
            $this->_displaysLabel = $displays[$this->display];
        }
        return $this->_displaysLabel;
    }
    public static function labels()
    {
        return [
            self::STATUS_INACTIVE=>"未使用",
            self::STATUS_USE => "使用中",
            self::STATUS_DELETED => "删除",
        ];
    }
    public static function displays()
    {
        return [
            self::DISPLAY_INACTIVE=>"手动添加",
            self::DISPLAY_USE => "自动添加",
        ];
    }
    public static function getCard(){
        $models = Card::find()->asArray()->all();
        return $models;
    }
    public static function createcode($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars, $length);

        $password = '';
        for($i = 0; $i < $length; $i++)
        {
            // 将 $length 个数组元素连接成字符串
            $password .= $chars[$keys[$i]];
        }

        return $password;
    }
}
