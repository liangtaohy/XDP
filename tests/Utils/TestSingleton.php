<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/1 13341007105@163.com
 * Time: 下午6:27
 */

namespace Xdp\Test\Utils;
require_once __DIR__ . "/../../vendor/autoload.php";


use PHPUnit\Framework\TestCase;
use Xdp\Utils\Traits\Singleton;

class TestSingleton extends TestCase
{
    public function testNewClass()
    {
        $this->assertEquals(testOne::getInstance(),testOne::getInstance());
    }
}

class testOne{
    use Singleton;

    private function __construct()
    {
    }
}