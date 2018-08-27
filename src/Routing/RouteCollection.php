<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/26
 * Time: 下午1:02
 */

namespace Xdp\Routing;

use Countable;
use ArrayIterator;
use IteratorAggregate;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Xdp\Http\Request;
use Xdp\Http\Response;
use Xdp\Utils\Arr;

class RouteCollection implements Countable, IteratorAggregate
{
    /**
     * 一个二维数组，{method, routes}
     *
     * @var array
     */
    protected $routes = [];

    /**
     * 所有route的集合, <fulluri, route>
     *
     * @var array
     */
    protected $allRoutes = [];

    /**
     * 新增一个路由
     *
     * @param Route $route
     * @return Route
     */
    public function add(Route $route)
    {
        $fullUri = $route->getDomain().$route->uri();

        foreach ($route->methods() as $method)
        {
            $this->routes[$method][$fullUri] = $route;
        }

        $this->allRoutes[$fullUri] = $route;

        return $route;
    }

    /**
     * @param Request $request
     * @param bool $includingMethod
     * @return $this
     */
    public function match(Request $request, $includingMethod = true)
    {
        $routes = $this->get($request->method());

        $route = Arr::first($routes, function($value) use ($request, $includingMethod) {
            return $value->matches($request, $includingMethod);
        });

        if (!is_null($route)) {
            return $route->bind($request);
        }

        $others = $this->checkForAlternateVerbs($request);

        if (count($others) > 0) {
            if ($request->getMethod() == "OPTIONS") {
                return $this->getOptionsRoute($request, $others);
            } else {
                throw new MethodNotAllowedHttpException($others);
            }
        }

        throw new NotFoundHttpException;
    }

    /**
     * 生成HTTP OPTIONS路由
     *
     * @param Request $request
     * @param array $methods
     * @return Route
     */
    protected function getOptionsRoute(Request $request, array $methods)
    {
        return (new Route('OPTIONS', $request->path(), function () use ($methods) {
            return new Response('', 200, ['Allow' => implode(',', $methods)]);
        }))->bind($request);
    }

    /**
     * 匹配一个路由
     *
     * @param array $routes
     * @param Request $request
     * @param bool $includingMethod
     * @return mixed
     */
    protected function matchFromRoutes(array $routes, Request $request, $includingMethod = true)
    {
        return Arr::first($routes, function($value) use ($request, $includingMethod) {
            return $value->matches($request, $includingMethod);
        });
    }

    /**
     * 查找是否有可选的路由
     *
     * @param $request
     * @return array
     */
    protected function checkForAlternateVerbs($request)
    {
        $methods = array_diff(Router::$verbs, [$request->getMethod()]);
        $others = [];
        foreach ($methods as $method) {
            $route = $this->matchFromRoutes($this->get($method), $request, false);
            if (!is_null($route)) {
                $others[] = $method;
            }
        }

        return $others;
    }

    /**
     * 获取$method对应的路由列表
     *
     * @param string $method
     * @return array|mixed
     */
    public function get(string $method)
    {
        return empty($method) ? $this->getRoutes() : Arr::get($this->routes, $method, []);
    }

    /**
     * 获取所有的路由
     *
     * @return array
     */
    public function getRoutes()
    {
        return array_values($this->allRoutes);
    }

    /**
     * 获取路由总数
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->allRoutes);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return ArrayIterator An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->allRoutes);
    }
}