<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 */


namespace tests;


use yii\base\ErrorException;
use yii\db\Connection;

class nihao
{
    public function aa($aa)
    {
        if ($aa) {
            return 'true';
        } else {
            throw new ErrorException('nihao buhao', 8000);
        }
    }
}

class NihaoTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function a()
    {
        $aa = 'aaa';
        $this->assertEquals($aa, 'aaaa');
    }

    /** @test */
    public function UserInvestTest()
    {
        $nihao = new nihao();
        var_dump($nihao->aa(false));

        $db = \Yii::$app->db;
        $db->transaction(function (Connection $db) {

            $db->transaction(function (Connection $db) {


            });

        });
    }
}
