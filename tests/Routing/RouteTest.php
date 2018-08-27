<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/27
 * Time: 下午4:17
 */

namespace Xdp\Test\Routing;

require __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Xdp\Routing\Route;
use Xdp\Http\Request;

class RouteTest extends TestCase
{
    public function testRouteMatching()
    {
        /**
         * just uri match
         */
        $route = new Route('GET', '/foo/bar', function() {});
        $request = Request::create("http://example.com/foo/bar", 'GET');
        $this->assertTrue($route->matches($request));

        /**
         * path uri match with parameters
         */
        $route = new Route('GET', '/{foo}/{bar?}', function() {});
        $this->assertTrue($route->matches($request));

        $request = Request::create("http://example.com/lotus/beer", 'GET');
        $route->bind($request);
        $this->assertEquals(['foo'=>'lotus', 'bar'=>'beer'], $route->parameters());
        $this->assertTrue($route->hasParameter('foo'));
        $this->assertEquals('lotus', $route->parameter('foo'));
        $this->assertEquals('notexisted', $route->parameter('hello', 'notexisted'));
    }

    public function testRouteMethods()
    {
        /**
         * valid method
         */
        $route = new Route('GET', '/foo/bar', function() {});
        $request = Request::create('http://example.com/foo/bar', 'GET');
        $this->assertEquals(['GET', 'HEAD'], $route->methods());
        $this->assertTrue($route->matches($request));
        $this->assertTrue(in_array('GET', $route->methods()));

        /**
         * unsupported method
         */
        $request = Request::create('http://example.com/foo/bar', 'POST');
        $this->assertFalse($route->matches($request));
    }

    public function testHttpOnly()
    {
        $route = new Route('GET', '/foo/bar', ['http', function() {echo "hello,world";}]);
        $request = Request::create('http://example.com/foo/bar', 'GET');
        $this->assertTrue($route->httpOnly());
        $this->assertFalse($route->httpsOnly());
        $this->assertTrue($route->matches($request));

        $request = Request::create('https://example.com/foo/bar', 'GET');
        $this->assertFalse($route->matches($request));
    }

    public function testHttpsOnly()
    {
        $route = new Route('GET', '/foo/bar', ['https', function() {echo "hello,world";}]);
        $request = Request::create('https://example.com/foo/bar', 'GET');
        $this->assertTrue($route->httpsOnly());
        $this->assertFalse($route->httpOnly());
        $this->assertTrue($route->matches($request));

        $request = Request::create('http://example.com/foo/bar', 'GET');
        $this->assertFalse($route->matches($request));
    }

    public function testAction()
    {
        $route = new RouteStub('GET', '/foo/bar', ['http', function(){echo "hello, world";}]);
        $action = $route->getAction();
        $this->assertTrue(isset($action['uses']));
        $this->assertTrue(is_array($action));
    }

    public function testMiddleware()
    {
        $route = new Route('GET', '/foo/bar', ['http', 'middleware'=>AuthMiddlewareStub::class]);
        $middleware = $route->middleware();
        $this->assertCount(1, $middleware);

        $route = new Route('GET', '/foo/bar', ['http', 'middleware'=>[AuthMiddlewareStub::class, AuthMiddlewareStub1::class]]);
        $this->assertCount(2, $route->middleware());
    }
}

class RouteStub extends Route
{
    public function getAction()
    {
        return $this->action;
    }
}

class AuthMiddlewareStub
{
    public function handle($request, $next)
    {
        return "hello, world!";
    }
}

class AuthMiddlewareStub1
{
    public function handle($request, $next)
    {
        return "One, hello, world!";
    }
}