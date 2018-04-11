<?php

namespace common\models\sinapay;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sina_config".
 *
 * @property integer $id
 * @property string $sinapay_site_prefix
 * @property string $sinapay_version
 * @property string $sinapay_partner_id
 * @property string $sign_type
 * @property string $sinapay_md5_key
 * @property string $sinapay_input_charset
 * @property string $sinapay_rsa_sign_private_key
 * @property string $sinapay_rsa_sign_public_key
 * @property string $sinapay_rsa_public__key
 * @property string $sinapay_mgs_url
 * @property string $sinapay_mas_url
 * @property string $sinapay_site_email
 * @property integer $sinapay_give_accrual
 * @property integer $create_at
 * @property integer $update_at
 */
class SinaConfig extends ActiveRecord
{
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
        return 'sina_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sinapay_rsa_sign_private_key', 'sinapay_rsa_sign_public_key', 'sinapay_rsa_public__key'], 'string'],
            [['sinapay_give_accrual', 'create_at', 'update_at'], 'integer'],
            [['sinapay_site_prefix', 'sinapay_partner_id', 'sinapay_md5_key', 'sinapay_input_charset', 'sinapay_mgs_url', 'sinapay_mas_url', 'sinapay_site_email'], 'string', 'max' => 255],
            [['sinapay_version', 'sign_type'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sinapay_site_prefix' => '网站创建新浪会员标识',
            'sinapay_version' => '接口版本',
            'sinapay_partner_id' => '商户号',
            'sign_type' => '签名方式',
            'sinapay_md5_key' => '商户MD5KEY秘钥',
            'sinapay_input_charset' => '商户接口字符集',
            'sinapay_rsa_sign_private_key' => '商户签名私钥',
            'sinapay_rsa_sign_public_key' => '商户验证签名公钥',
            'sinapay_rsa_public__key' => '新浪支付特殊参数加密，公钥',
            'sinapay_mgs_url' => '会员类网关地址',
            'sinapay_mas_url' => '订单类网关地址',
            'sinapay_site_email' => '企业钱包登录名邮箱',
            'sinapay_give_accrual' => '网站中用户返利的用户',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
