<?php
namespace www\models;

use common\models\UcenterMember;
use yii\base\Model;
use Yii;
use yii\base\InvalidParamException;
use yii\base\ErrorException;
use frontend\actions\Port;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    //public $email;
    public $password;
    public $password_repeat;
    public $validate_code;
    public $invitation_code;
    public  $agreement;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
        	//[['agreement'],'required','message'=>'请先同意用户协议及投资人注册协议'],
            [['username','validate_code','password','password_repeat','agreement'], 'required','message'=>'请输入{attribute}'],
            ['username', 'unique', 'targetClass' => '\common\models\UcenterMember', 'message' => '手机号已存在。'],
            ['username', 'string', 'min' => 2, 'max' => 255],
        	['username','match','pattern'=>'/^1[0-9]{10}$/','message'=>'请输入正确的{attribute}'],

           /*  ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'], */

            ['password', 'string', 'min' => 6],
        		
        	['password_repeat','compare','compareAttribute'=>'password','message'=>'两次录入的密码不一致。'],
        	['invitation_code','invitationCode'],
        	['validate_code','validateCode'],
        ];
    }
    
    public function attributeLabels()
    {
    	return [
    			'username'=>'手机号',
    			'validate_code'=>'验证码',
    			'password'=>'登录密码',
    			'password_repeat'=>'确认密码',
    			'invitation_code' => '邀请码',
    			'agreement'=>"",
    	];
    }
    
    /**
     * 验证手机验证码
     */
    
    public function validateCode($attribute,$params)
    {
    	try 
    	{
    	    $result = Port::checkPhnoe($this->username, $this->validate_code);
    	}
    	catch (ErrorException $ex)
    	{
    		$this->addError($attribute, Yii::t('app', '验证码无效，请重新获取。'));
    	}
    	
    }
    
    /**
     * 验证邀请码
     */
    
    public function invitationCode($attribute,$params)
    {
    	$model = UcenterMember::findOne(['invitation_code' => $this->invitation_code]);
    	if(!$model)
    	{
    		$this->addError($attribute, Yii::t('app', '邀请码无效。'));
    	}
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new UcenterMember();
            $user->username = $this->username;
            $user->phone = $this->username;
            $user->setPassword($this->password);
            $user->create_ip=Yii::$app->request->userIp;
            $app_pwd = md5(sha1($this->password).time());
            $user->app_pwd = $app_pwd;
            try {
            $area = self::get_area(Yii::$app->request->userIp);
            $user->create_area = $area;
            }
            catch (ErrorException $e)
            {
            	
            }
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
   
    
}
