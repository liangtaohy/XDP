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
use Xdp\Routing\Controller;
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
        try {
            $response = $router->dispatch(Request::create('/foo/bar', $method='GET'));
        } catch (\LogicException $e) {
            $this->assertNotEmpty($e);
            echo PHP_EOL . $e->getCode() . ": " . $e->getMessage() . PHP_EOL;
        }

        $route = $router->get("/foo/bar", function () { return "hello, world"; });
        $response = $router->dispatch(Request::create('/foo/bar', $method='GET'));
        //echo $response . PHP_EOL;
        $this->assertEquals('hello, world', $response->getContent());

        $route = $router->get("/foo/bar", function () { return ['code'=>0]; });
        $response = $router->dispatch(Request::create('/foo/bar', $method='GET'));

        $this->assertEquals('{"code":0}', $response->getContent());
        echo $response . PHP_EOL;

        $route = $router->get("/api/say/{name}", HelloWorldController::class . "@say");
        $response = $router->dispatch(Request::create('/api/say/Lotus', $method='GET'));

        $this->assertTrue($route->isControllerAction());
        $this->assertEquals('Lotus, how are you?', $response->getContent());

        $route = $router->get("/api/got/{name}", JsonController::class . "@got");
        $response = $router->dispatch(Request::create('/api/got/Lotus', $method='GET'));

        $this->assertTrue($route->hasParameter('name'));
        $this->assertTrue($route->isControllerAction());
        $this->assertEquals('{"user":"Lotus"}', $response->getContent());

        $route = $router->get("/api/got/{name}", JsonController::class . "@notfound");
        try {
            $response = $router->dispatch(Request::create('/api/got/Lotus', $method='GET'));
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof \BadMethodCallException);
        }
    }
}

class HelloWorldController extends Controller
{
    public function say($name)
    {
        return $name . ", how are you?";
    }
}

class JsonController extends Controller
{
    public function got($name)
    {
        return ['user'=>$name];
    }
}