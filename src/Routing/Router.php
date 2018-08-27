<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/27
 * Time: 上午9:56
 */

namespace Xdp\Routing;

use Xdp\Container\Container;
use Xdp\Contract\Support\Arrayable;
use Xdp\Contract\Support\Jsonable;
use Xdp\Contract\Support\Responsable;
use Xdp\Http\JsonResponse;
use Xdp\Http\Request;
use Xdp\Http\Response;
use ArrayObject;
use JsonSerializable;
use Xdp\Pipeline\Pipeline;

class Router
{
    protected $container;

    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * 当前请求
     *
     * @var \Xdp\Http\Request
     */
    protected $request;

    protected $middleware;

    /**
     * 当前路由
     *
     * @var \Xdp\Routing\Route
     */
    protected $currentRoute;

    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->routes = new RouteCollection;
    }

    /**
     * 添加GET类型的route
     *
     * @param $uri
     * @param \Closure|array|string $action
     * @return Route
     */
    public function get($uri, $action = null)
    {
        return $this->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    /**
     * 添加POST类型的route
     *
     * @param $uri
     * @param \Closure|array|string $action
     * @return Route
     */
    public function post($uri, $action = null)
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * 添加PUT类型的route
     *
     * @param $uri
     * @param \Closure|array|string $action
     * @return Route
     */
    public function put($uri, $action = null)
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * 添加DELETE类型的route
     *
     * @param $uri
     * @param \Closure|array|string $action
     * @return Route
     */
    public function delete($uri, $action = null)
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * 添加OPTIONS类型的route
     *
     * @param $uri
     * @param \Closure|array|string $action
     * @return Route
     */
    public function options($uri, $action = null)
    {
        return $this->addRoute('OPTIONS', $uri, $action);
    }

    /**
     * 添加PATCH类型的route
     *
     * @param $uri
     * @param \Closure|array|string $action
     * @return Route
     */
    public function patch($uri, $action = null)
    {
        return $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * 注册一个新的路由到router
     *
     * @param array|string $methods
     * @param string $uri
     * @param \Closure|array|string $action
     * @return Route
     */
    public function match($methods, $uri, $action)
    {
        return $this->addRoute(array_map('strtoupper', (array)$methods), $uri, $action);
    }

    /**
     * 派发request
     *
     * @param Request $request
     * @return Response
     */
    public function dispatch(Request $request)
    {
        $this->request = $request;
        return $this->runRoute($request, $this->findRoute($request));
    }

    /**
     * 查找与请求匹配的路由
     *
     * @param Request $request
     * @return Route
     */
    public function findRoute(Request $request)
    {
        $this->currentRoute = $this->routes->match($request);

        return $this->currentRoute;
    }

    /**
     * 执行指定的route
     *
     * @param Request $request
     * @param Route $route
     * @return Response
     */
    public function runRoute(Request $request, Route $route)
    {
        $middlewares = $route->gatherMiddleware();

        return (new Pipeline($this->container))
            ->send($request)
            ->through($middlewares)
            ->then(function ($request) use ($route) {
                return $this->prepareResponse($request, $route->run());
            });
    }

    /**
     * 准备response
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function prepareResponse($request, $response)
    {
        return static::toResponse($request, $response);
    }

    /**
     * 转换response
     *
     * @param \Xdp\Http\Request $request
     * @param \Xdp\Http\Response $response
     * @return \Xdp\Http\Response
     */
    public static function toResponse($request, $response)
    {
        if ($response instanceof Responsable) {
            $response = $response->toResponse($request);
        }

        if ($response instanceof Jsonable
            || $response instanceof Arrayable
            || $response instanceof ArrayObject
            || $response instanceof JsonSerializable
            || is_array($response)) {
            $response = new JsonResponse($response);
        }

        return $response->prepare($request);
    }

    /**
     * 添加route
     *
     * @param string|array $methods
     * @param $uri
     * @param null $action
     * @return Route
     */
    protected function addRoute($methods, $uri, $action = null)
    {
        return $this->routes->add($this->createRoute($methods, $uri, $action));
    }

    /**
     * 生成一个路由
     *
     * @param $methods
     * @param $uri
     * @param null $action
     * @return Route
     */
    protected function createRoute($methods, $uri, $action = null)
    {
        if ($this->isReferenceControllerAction($action)) {
            $action = $this->convertToControllerAction($action);
        }

        $route = new Route($methods, $uri, $action);
        $route->setContainer($this->container)->setRouter($this);
        return $route;
    }

    /**
     * 检查controller action的类型
     *
     * @param $action
     * @return bool
     */
    protected function isReferenceControllerAction($action)
    {
        if (! $action instanceof \Closure) {
            return is_string($action) || (isset($action['uses']) && is_string($action['uses']));
        }

        return false;
    }

    /**
     * 转换controller action
     *
     * @param $action
     * @return array
     */
    protected function convertToControllerAction($action)
    {
        if (is_string($action)) {
            $action = ['uses' => $action];
        }

        $action['controller'] = $action['uses'];
        return $action;
    }
}