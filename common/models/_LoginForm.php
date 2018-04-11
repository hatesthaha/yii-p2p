<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\UcenterMember;
use common\models\base\ucenter\Log;
use yii\base\ErrorException;

/**
 * Login form
 */
class _LoginForm extends Model
{
	const ERROR_NUM=3;  //设置密码可输入错误的最多次数，否则锁定
	
    public $username;
    public $password;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', '手机号'),
            'password' => Yii::t('app', '密码'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
            	$this->addError($attribute, Yii::t('app', '用户名或密码不正确（密码错误超过三次自动锁死）'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login($username,$password)
    {
           $user = $this->_getUser($username);
           if( $user && $user->validatePassword($password))
           {
	           	$model = UcenterMember::find()->where("username=".$username)->one();
    			$result = UcenterMember::find()->where("username=".$username)->count();
	           	if($model)
	           	{
	           		$error_num = $model->error_num;
	           		if($model->lock == 1)
	           		{
	           			echo "用户已锁定，请联系管理员";
	           			exit;
	           		}
	           	}
	           	//记录登录IP
	           	$this->_user->login_ip=Yii::$app->request->userIp;
	           	$this->_user->error_num=0;
	           	if($this->_user->save())
	           	{
	           		$session = yii::$app->session;
	           		$session->open();
	           		$session['last_time'] = $model->updated_at;
		           	echo "登陆成功";
		           	if(Yii::$app->user->login($this->_getUser($username)))
		           	{
			           	$model_log = new Log();
			           	$model_log->member_id = yii::$app->user->id;
			           	$model_log->login_ip = yii::$app->request->userIp;
			           	try 
			           	{
			           		$model_log->login_area = self::get_area(yii::$app->request->userIp);
			           	}
			           	catch (ErrorException $e)
			           	{
			           		$model_log->login_area = '未知';
			           	}
			           	$model_log->login_time = strtotime("now");
			           	$model_log->status = 1;
			           	$model_log->save(false);
		           	}
		           	exit;
	           	}
	           	
           }
    	elseif($user)
    	  {
    		$model = UcenterMember::find()->where("username=".$username)->one();
    		$result = UcenterMember::find()->where("username=".$username)->count();
    		$error_num = $model->error_num;
    		if($result == 1)
    		{
    			if(self::ERROR_NUM < $error_num+2)
    			{
    				$model->lock = UcenterMember::TYPE_LOCK;
    				$model->save(false);
    				echo "用户已锁定，请联系管理员";
    				exit;
    			}
    			else
    			{
    				$model->error_num += 1;
    				$model->save(false);
    				echo "密码输入错误（超过".self::ERROR_NUM."次后锁定）";
    				exit;
    			}
    		}
    	}
    	else 
    	{
    		echo "此用户未注册";
    		exit;
    	}
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UcenterMember::findByUsername($this->username);
        }

        return $this->_user;
    }
    
    public function _getUser($username)
    {
    	if ($this->_user === false) {
    		$this->_user = UcenterMember::findByUsername($username);
    	}
    
    	return $this->_user;
    }
    
    //根据IP获取所在地区
    public static function get_area($ip)
    {
    
    	//        $ip = '120.3.255.92';
    	$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
    	if (empty($res)) {
    		throw new \ErrorException('获取注册地区失败', 1010);
    	}
    	$jsonMatches = array();
    	preg_match('#\{.+?\}#', $res, $jsonMatches);
    	if (!isset($jsonMatches[0])) {
    		throw new ErrorException('获取注册地区失败', 1010);
    	}
    	$json = json_decode($jsonMatches[0], true);
    	if (isset($json['ret']) && $json['ret'] == 1) {
    		$json['ip'] = $ip;
    		unset($json['ret']);
    	} else {
    		throw new ErrorException('获取注册地区失败', 1010);
    	}
    	$area = $json['country'] . '-' . $json['province'] . '-' . $json['city'];
    	return $area;
    }
}
