<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 上午10:07
 */

namespace XdpTest\Pipeline;

require_once __DIR__ . "/../../vendor/autoload.php";


use PHPUnit\Framework\TestCase;
use Xdp\Pipeline\FingersCrossedProcessor;
use Xdp\Pipeline\Pipeline;
use Xdp\Pipeline\SuspendProcessor;

class TestPipe extends TestCase
{

    public function testSomething()
    {
        $ret = (new Pipeline())
            ->pipe(new PipelineTestPipeOne())
            ->pipe(new PipelineTestPipeTwo())
            ->process(['name'=>'zhangsan','pwd'=>'lisi']);
        $this->assertEquals([ 'pwd' => 'lisi'], $ret);
    }

    public function testFingersCrossedProcessor()
    {
        $ret =(new FingersCrossedProcessor())
            ->process(
                ['name'=>'shiwenyuan','pwd'=>'1231312'],
                new PipelineTestPipeTwo(),
                new PipelineTestPipeOne()
            );
        $this->assertEquals([ 'pwd' => '1231312'], $ret);
    }

    public function testSuspendProcessor()
    {
        $ret =(new SuspendProcessor(function ($payload) {
               return 2 < count($payload) ? true : false;
        }))
            ->process(
                [1,2,3,4,5,5,6,7,8],
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree(),
                new PipelineTestPipeThree()
            );
        $this->assertEquals([21], $ret);
    }
}


class PipelineTestPipeOne
{
    public function __invoke($payload)
    {
        unset($payload['name']);
        return $payload;
    }
}


class PipelineTestPipeTwo
{
    public function __invoke($payload)
    {
        return $payload;
    }
}

class PipelineTestPipeThree
{
    public function __invoke($payload)
    {
        $count = count($payload);
        unset($payload[$count-1]);
        return $payload;
    }
}
