<?php

namespace common\models\base\experience;

use common\models\UcenterMember;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\base\experience\Rule;
/**
 * This is the model class for table "experience_gold".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $rid
 * @property double $money
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class Gold extends ActiveRecord
{
    //开启规则
    const STATUS_ACTIVE = 1;
    //关闭规则
    const STATUS_DELETED = -1;
    private $_statusLabel;
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'experience_gold';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'rid', 'status'], 'integer'],
            [['money'], 'number'],
            [['created_at', 'updated_at', 'title'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', '用户'),
            'rid' => Yii::t('app', '体验金类型'),
            'money' => Yii::t('app', '金额'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'title' => Yii::t('app', '名称'),
        ];
    }
    public function getRule()
    {
        return $this->hasOne(Rule::className(), ['id' => 'rid']);
    }
    public function getUser()
    {
        return $this->hasOne(UcenterMember::className(), ['id' => 'uid']);
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
            self::STATUS_ACTIVE=>"启用",
            self::STATUS_DELETED => "删除",
        ];
    }
}
