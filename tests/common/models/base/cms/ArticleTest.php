<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 */


namespace tests\common\models\base\cms;


interface interfacen
{
    public function in();
}

class nihao implements interfacen
{

    public function in()
    {
        var_dump(111);
    }
}

class nihao1 implements interfacen
{
    public function aa()
    {
        var_dump(555);
    }

    public function in()
    {
        var_dump(222);
    }
}

class bb
{
    public static function bbb(interfacen $interfacen)
    {
        $interfacen->in();
    }
}

class ArticleTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function aa()
    {
        $aa     = [

        ];
        $nihao  = new nihao();
        $nihao1 = new nihao1();
        bb::bbb($nihao1);
    }
}
