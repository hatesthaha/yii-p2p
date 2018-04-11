<?php
/**
 * Created by PhpStorm.
 * User: wly
 * Date: 2015/7/7
 * Time: 16:03
 */
namespace frontend\actions;

use common\models\base\fund\product;
use framework\base\ErrorException;


class Fund extends Action
{
    /**
     * 获取投资列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function fundList()
    {
        $fund_list =  Product::find()->asArray()->all();
        return $fund_list;

    }

    /**
     * 获取单个投资项目信息
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws ErrorException
     */
    public static function fundOne($id)
    {
        if(is_numeric($id))
        {
            $fund_one = product::find()->where(['id' => $id])->asArray()->one();
            if($fund_one){
                return $fund_one;
            }
            else{
                throw new ErrorException("项目不存在","100");
            }

        }else{
            throw new ErrorException('参数错误','404');
        }

    }

    public static function invest($uid,$pid,$money)
    {
        //判断用户状态TODO（是否锁定，是否实名）

        //获取用户账户金额

        //判断输入金额和账户金额/项目可投金额


        //判断通过----执行投资流程



    }

    public static function fundCreditor($pid)
    {

    }


}