<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午7:12
 */

namespace XdpTest\Container;

require_once __DIR__ . "/../../vendor/autoload.php";


use PHPUnit\Framework\TestCase;
use Xdp\Container\Container;

class TestContainer extends TestCase
{
    public function testContainerArray()
    {
        $con = new Container();
        $con['XdpTest\Container\testCaseOne'] = function () {
            return new testCaseOne();
        };
        $test_case = $con['XdpTest\Container\testCaseOne'];
        $this->assertEquals($test_case, new testCaseOne());
    }

    /*public function testContainerObj()
    {
        $con = new Container();
        $con->add('XdpTest\Container\testCaseOne', function () {return new testCaseOne();});
        $test_case = $con->get('XdpTest\Container\testCaseOne');
        $this->assertEquals($test_case, new testCaseOne());
    }

    public function testContainerHas()
    {
        $con = new Container();
        $con->add(\XdpTest\Container\testCaseOne::class, function () {return new testCaseOne();});
        $ret = $con->has(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($ret, true);
    }

    public function testContainerResolve()
    {
        $con = new Container();
        $one = $con->resolve(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($one, new testCaseOne());
        $class = $con->resolve(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($class, new testCaseOne());
        $return = $con->resolveMethod(new testCaseTwo('shiwenyuan'), 'getName', ['name' => 'zhangsan']);
        $this->assertEquals($return, 'zhangsan');
        $three = $con->resolve(\XdpTest\Container\testCaseThree::class);
        $this->assertEquals($three->getname(), '石文远');
    }

    public function testContainerAddInstance()
    {
        $con = new Container();
        $con->addInstance(new \XdpTest\Container\testCaseOne);
        $one = $con->get(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($one, new testCaseOne());
    }

    public function testContainerAddSingleton()
    {
        $con = new Container();
        $con->addSingleton('\XdpTest\Container\testCaseOne',function (){ return new \XdpTest\Container\testCaseOne();});
        $one = $con->get('\XdpTest\Container\testCaseOne');
        $this->assertEquals($one, new testCaseOne());
    }*/
}

class testCaseOne
{
    public $name;

    public function __construct()
    {
        $this->name = '石文远';
    }

    public function getName()
    {
        echo $this->name;
    }
}


class testCaseTwo
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName($name)
    {
        return $name;
    }
}

class testCaseThree
{
    public function __construct(testCaseOne $class)
    {
        $this->clsss = $class;
    }

    public function getname()
    {
        return $this->clsss->name;
    }
}
