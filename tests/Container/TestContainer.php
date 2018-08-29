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

    /**
     * 测试别名
     * @throws \Xdp\Container\Exception\ContainerException
     * @throws \Xdp\Container\Exception\KeyExistsException
     * @throws \Xdp\Container\Exception\NotFoundException
     */
    public function testContainerAddAlias()
    {
        $con = new Container();
        $con['shiwenyuan'] = function () {
            return new \XdpTest\Container\testCaseOne();
        };
        $con->addAlias('s', 'shiwenyuan');
        $this->assertEquals($con['s'], $con->get('shiwenyuan'));

        $con = new Container();
        $test_case1 = $con->make(\XdpTest\Container\testCaseOne::class);
        $test_case2 = $con->get(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($test_case1, $test_case2);

        $con = new Container();
        $con->addInstance('test', new \XdpTest\Container\testCaseOne())->addAlias('t', 'test');
        $this->assertEquals($con['t'], new \XdpTest\Container\testCaseOne());

        $con = new Container();
        $test_case1 = $con->make('\XdpTest\Containe', '\XdpTest\Container\testCaseOne', 'shiwenyuan');
        $test_case2 = $con->get('\XdpTest\Containe');
        $this->assertEquals($test_case1, $test_case2);

        $con = new Container();
        $test_case1 = $con->make(\XdpTest\Container\testCaseOne::class);
        $test_case2 = $con->get(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($test_case1, $test_case2);

        $con->instance("path", __DIR__);
        $this->assertEquals(__DIR__, $con['path']);
    }

    /**
     *
     */
    public function testContainerArray()
    {
        $con = new Container();
        $con['XdpTest\Container\testCaseOne'] = function () {
            return new testCaseOne();
        };
        $test_case = $con['XdpTest\Container\testCaseOne'];
        $this->assertEquals($test_case, new testCaseOne());
    }


    /**
     * @throws \Xdp\Container\Exception\ContainerException
     * @throws \Xdp\Container\Exception\KeyExistsException
     * @throws \Xdp\Container\Exception\NotFoundException
     */
    public function testContainerObj()
    {
        $con = new Container();
        $con->add('XdpTest\Container\testCaseOne', function () {return new testCaseOne();});
        $test_case = $con->get('XdpTest\Container\testCaseOne');
        $this->assertEquals($test_case, new testCaseOne());
    }

    /**
     * @throws \Xdp\Container\Exception\KeyExistsException
     */
    public function testContainerHas()
    {
        $con = new Container();
        $con->add(\XdpTest\Container\testCaseOne::class, function () {return new testCaseOne();});
        $ret = $con->has(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($ret, true);

        $con = new Container();
        $con->addSingleton('shiwenyuan',new testCaseOne());
        $this->assertEquals($con->has('shiwenyuan'),true);
        $this->assertEquals($con->has('shiwenyuan1'),false);
    }

    /**
     * @throws \Xdp\Container\Exception\ContainerException
     */
    public function testContainerResolve()
    {
        $con = new Container();
        $one = $con->resolve(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($one, new testCaseOne());

        $return = $con->resolveMethod(new testCaseTwo('shiwenyuan'), 'getName', ['name' => 'zhangsan']);
        $this->assertEquals($return, 'zhangsan');

        $three = $con->resolve(\XdpTest\Container\testCaseThree::class);
        $this->assertEquals($three->getname(), '石文远');

        $three = $con->resolve('\XdpTest\Container\testCaseThree');
        $this->assertEquals($three->getname(), '石文远');
    }

    /**
     * @throws \Xdp\Container\Exception\ContainerException
     * @throws \Xdp\Container\Exception\KeyExistsException
     * @throws \Xdp\Container\Exception\NotFoundException
     */
    public function testContainerAddInstance()
    {
        $con = new Container();
        $con->addInstance(new \XdpTest\Container\testCaseOne);
        $one = $con->get(\XdpTest\Container\testCaseOne::class);
        $this->assertEquals($one, new testCaseOne());
    }

    /**
     * @throws \Xdp\Container\Exception\ContainerException
     * @throws \Xdp\Container\Exception\KeyExistsException
     * @throws \Xdp\Container\Exception\NotFoundException
     */
    public function testContainerAddSingleton()
    {
        $con = new Container();
        $con->addSingleton('\XdpTest\Container\testCaseOne',function (){ return new \XdpTest\Container\testCaseOne();});
        $this->assertEquals($con->get('\XdpTest\Container\testCaseOne'), $con->get('\XdpTest\Container\testCaseOne'));

        $con = new Container();
        $con->addSingleton('\XdpTest\Container\testCaseOne',new \XdpTest\Container\testCaseOne());
        $this->assertEquals($con->get('\XdpTest\Container\testCaseOne'), $con->get('\XdpTest\Container\testCaseOne'));
    }
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
