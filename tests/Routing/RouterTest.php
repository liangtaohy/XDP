<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/27
 * Time: 下午3:17
 */

namespace Xdp\Test\Routing;

require __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Xdp\Routing\Router;
use Xdp\Routing\Route;
use Xdp\Container\Container;
use Xdp\Http\Response;
use Xdp\Http\Request;

class RouterTest extends TestCase
{
    public function testGetRoute()
    {
        $router = new Router(new Container());
        $route = $router->get("/foo/bar");
        var_dump($route->action);
        //$response = $route->run();
        $response = $router->dispatch(Request::create('/foo/bar', $method='GET'));
        echo $response . PHP_EOL;
    }
}