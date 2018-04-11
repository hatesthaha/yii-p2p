<?php

namespace common\models;

use common\models\base\asset\Info;
use Yii;

use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use common\models\base\ucenter\Cat;
use common\models\base\ucenter\Catmiddle;
/**
 * This is the model class for table "ucenter_member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $phone
 * @property string $email
 * @property string $idcard
 * @property string $real_name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $create_ip
 * @property string $create_area
 * @property integer $create_channel
 * @property integer $parent_member_id
 * @property integer $vip
 * @property integer $lock
 * @property integer $type
 * @property ActivityRaiseCard[] $activityRaiseCards
 * @property AssetInfo $assetInfo
 * @property AssetLog[] $assetLogs
 * @property FundOrders[] $fundOrders
 * @property UcenterLog[] $ucenterLogs
 * @property UcenterMember $parentMember
 * @property UcenterMember[] $ucenterMembers
 * @property WechatInfo $wechatInfo
 */
class UcenterMember extends ActiveRecord implements IdentityInterface
{

    //注册
	const STATUS_ACTIVE = 1;
    //实名认证
    const STATUS_REAL = 2;
    //绑定银行卡
    const STATUS_BIND = 3;

    //客户
    //const CUS_MEM = 1;

    //原始债权人
    const CUS_CRE = 2;
    //最大债权人
    const CUS_MAXCRE = 1;

    //锁定用户
    const TYPE_LOCK = 1;
    //正常用户
    const TYPE_UNLOCK = 0;
    //黑名单
    const TYPE_BLOCK = -1;
    //删除账户
    const TYPE_DELETE = -2;

    private $_statusLabel;
    private $_typeLabel;
    private $_cusLabel;
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
				TimestampBehavior::className(),
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ucenter_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash'], 'required'],
            [['status', 'lock','created_at', 'updated_at', 'create_channel', 'parent_member_id', 'vip'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 15],
            [['real_name', 'create_ip', 'create_area'], 'string', 'max' => 100],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_REAL, self::STATUS_BIND]],
            ['lock', 'in', 'range' => [self::TYPE_LOCK, self::TYPE_UNLOCK, self::TYPE_BLOCK,self::TYPE_DELETE]],
            [['idcard'], 'unique'],
            [['phone'], 'unique'],

        	['person_face', 'file', 'skipOnEmpty'=>true,'maxSize'=>1048576,'tooBig'=>'文件不能超过1M','checkExtensionByMimeType'=>false, 'extensions'=>'jpg,gif,png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '会员id'),
            'username' => Yii::t('app', '用户名'),
        	'person_face' => Yii::t('app', ''),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'phone' => Yii::t('app', '手机号'),
            'email' => Yii::t('app', '邮箱'),
            'idcard' => Yii::t('app', '身份证信息，唯一索引'),
            'real_name' => Yii::t('app', '用户真实姓名'),
            'status' => Yii::t('app', '用户状态'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'create_ip' => Yii::t('app', '创建时候的ip'),
            'create_area' => Yii::t('app', '创建时候的地区'),
            'create_channel' => Yii::t('app', '创建时候的 渠道'),
            'parent_member_id' => Yii::t('app', '邀请人'),
            'vip' => Yii::t('app', '分数，设置填写权重'),
            'login_ip' => Yii::t('app', '登陆地点'),
            'person_face' => '',
            'app_pwd' => 'App Pwd',
            'lock' =>'锁定状态',
            'type' => '类型',
        ];
    }

    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel =  array_key_exists($this->status,self::getArrayStatus()) ? $statuses[$this->status] : $statuses[self::STATUS_ACTIVE];
        }
        return $this->_statusLabel;
    }
    public function getTypeLabel()
    {
        if ($this->_typeLabel === null) {
            $statuses = self::getTypeStatus();
            $this->_typeLabel = $statuses[$this->lock];
        }
        return $this->_typeLabel;
    }
    public function getCusLabel()
    {
        if ($this->_cusLabel === null) {
            $statuses = self::getCusStatus();
            $this->_cusLabel = array_key_exists($this->type,self::getCusStatus()) ? $statuses[$this->type] : '无';

        }
        return $this->_cusLabel;
    }
    public static function getCusStatus()
    {
        $cat = Cat::find()->select('id,name')->asArray()->all();
        $name = array_column($cat, 'name','id');
        return  $name ;
    }
    public static function getArrayStatus()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', '注册'),
            self::STATUS_REAL => Yii::t('app', '实名认证'),
            self::STATUS_BIND => Yii::t('app', '绑定银行卡'),
        ];
    }
    public static function getTypeStatus()
    {
        return [
            self::TYPE_LOCK => Yii::t('app', '锁定用户'),
            self::TYPE_UNLOCK => Yii::t('app', '正常用户'),
            self::TYPE_BLOCK => Yii::t('app', '黑名单'),
            self::TYPE_DELETE => Yii::t('app', '删除用户'),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
    	return static::findOne(['id' => $id]);
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    	throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
    	return static::findOne(['username' => $username]);
    }
    
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
    	if (!static::isPasswordResetTokenValid($token)) {
    		return null;
    	}
    
    	return static::findOne([
    			'password_reset_token' => $token
    	]);
    }

    public static function weekreg(){
        return $weekpeople = self::find()
            ->andWhere(['>=', 'created_at', time() - 7 * 24 * 3600])
            ->count();
    }
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
    	if (empty($token)) {
    		return false;
    	}
    	$expire = Yii::$app->params['user.passwordResetTokenExpire'];
    	$parts = explode('_', $token);
    	$timestamp = (int) end($parts);
    	return $timestamp + $expire >= time();
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
    	return $this->getPrimaryKey();
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
    	return $this->auth_key;
    }
    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
    	return $this->getAuthKey() === $authKey;
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
    	return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
    	$this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
    	$this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
    	$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * 联合查询
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(Info::className(), ['member_id' => 'id']);
    }
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
    	$this->password_reset_token = null;
    }

}
