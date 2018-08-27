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
use Xdp\Container\Container;
use Xdp\Contract\Pipeline\MiddlewareInterface;
use Xdp\Pipeline\Pipeline;
use Closure;

class TestPipe extends TestCase
{

    public function testSomething()
    {
        $app = new Container();
        $data = (new Pipeline($app))
            ->send(1)
            ->through([PipelineTestPipeOne::class, 'XdpTest\Pipeline\PipelineTestPipeTwo:a,b,c'])
            ->then(function ($paied) {
                return $paied;
            });
        $this->assertEquals($data, 5);
    }

    public function testViaHandle()
    {
        $app = new Container();
        $data = (new Pipeline($app))
            ->via('invoke')
            ->send(1)
            ->through([PipelineTestPipeOne::class, 'XdpTest\Pipeline\PipelineTestPipeTwo:a,b,c'])
            ->then(function ($paied) {
                return $paied;
            });
        $this->assertEquals($data, 3);
    }

    public function testSuspend()
    {
        $app = new Container();
        $data = (new Pipeline($app))
            ->send(2)
            ->through([
                PipelineTestPipeOne::class,
                function ($request, \Closure $next) {
                    return $request;
                },
                PipelineTestPipeTwo::class,
            ])
            ->then(function ($paied) {
                return $paied;
            });
        $this->assertEquals($data, 3);
    }
}


class PipelineTestPipeOne implements MiddlewareInterface
{
    public function handle($request, \Closure $next)
    {
        return $next($request+2);
    }

    public function invoke($request, \Closure $next)
    {
        $request++;

        return $next($request);
    }
}


class PipelineTestPipeTwo implements MiddlewareInterface
{

    public function invoke($request, Closure $next, $a, $b, $c)
    {
        $request++;
        return $next($request);
    }

    public function handle($request, Closure $next)
    {
        return $next($request+2);
    }
}

