<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/24
 * Time: 下午3:09
 */

namespace Xdp\Routing;

use Closure;
use Xdp\Http\Request;
use Xdp\Routing\Matching\MethodValidator;
use Xdp\Routing\Matching\SchemeValidator;
use Xdp\Routing\Matching\UrlValidator;
use Xdp\Utils\Arr;
use Xdp\Utils\Str;
use Symfony\Component\Routing\Route as SymfonyRoute;
use LogicException;

class Route
{
    use RouteDependenciesTrait;

    /**
     * route所属router
     *
     * @var \Xdp\Routing\Router
     */
    public $router;

    /**
     * route所属容器
     *
     * @var \Xdp\Container\Container
     */
    public $container;

    /**
     * 路由响应的Uri pattern
     *
     * @var
     */
    public $uri;

    /**
     * 路由响应的Http Methods
     * @var
     */
    public $methods;

    /**
     * Action Array
     * @note action的合法形式如下：
     *      ['http', 'uses' => function() {}],
     *      function () {},
     *      ['http', 'uses' => 'ControllerConcrete@index']
     * @var
     */
    public $action;

    /**
     * controller实例
     *
     * @var \Xdp\Contract\Routing\Controller
     */
    public $controller;

    /**
     * 路由参数
     *
     * @var
     */
    public $parameters;

    /**
     * compiled route
     *
     * @var
     */
    public $compiled;

    /**
     * 中间件集合
     *
     * @var array
     */
    protected $computedMiddlewares;

    protected $wheres = [];

    protected $defaults = [];

    /**
     * Route constructor.
     *
     * @param string|array $methods
     * @param string $uri
     * @param array|string|\Closure$action
     */
    public function __construct($methods, $uri, $action = null)
    {
        $this->uri = $uri;
        $this->methods = (array) $methods;

        if (in_array('GET', $this->methods) && ! in_array('HEAD', $this->methods)) {
            $this->methods[] = 'HEAD';
        }

        $this->action = $this->parseAction($uri, $action);
    }

    /**
     * 设置container
     *
     * @param $container
     * @return $this
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * 设置router
     *
     * @param $router
     * @return $this
     */
    public function setRouter($router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * 返回uri pattern
     *
     * @return mixed
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * 设置uri pattern
     *
     * @param $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * 返回route以及controller上的所有middleware
     *
     * @return array
     */
    public function gatherMiddleware()
    {
        if (! is_null($this->computedMiddlewares)) {
            return $this->computedMiddlewares;
        }

        $this->computedMiddlewares = [];
        return $this->computedMiddlewares = array_unique(array_merge($this->middleware(), $this->controllerMiddleware()));
    }

    public function controllerMiddleware()
    {
        if (! $this->isControllerAction()) {
            return [];
        }

        return $this->controllerDispatcher()->getMiddleware($this->getController(), $this->getControllerMethod());
    }

    /**
     * 获取Controller实例
     *
     * @return object|\Xdp\Contract\Routing\Controller
     */
    public function getController()
    {
        if (! $this->controller) {
            $controller = $this->parseControllerCallback()[0];
            $this->controller = $this->container->resolve($controller);
            var_dump($this->controller);
        }

        return $this->controller;
    }

    /**
     * 获取controller method
     *
     * @return mixed
     */
    public function getControllerMethod()
    {
        return $this->parseControllerCallback()[1];
    }

    /**
     * 获取middleware或设置middleware
     *
     * @param array|string $middleware
     * @return $this|array
     */
    public function middleware($middleware = null)
    {
        if (is_null($middleware)) {
            return (array)($this->action['middleware'] ?? []);
        }

        if (is_string($middleware)) {
            $middleware = func_get_args();
        }

        $this->action['middleware'] = array_merge((array)($this->action['middleware'] ?? []), $middleware);
        return $this;
    }

    /**
     * 设置路由的域
     *
     * @param string $domain
     * @return $this
     */
    public function setDomain(string $domain)
    {
        if (is_null($domain) || empty($domain)) {
            return $this;
        }

        $this->action['domain'] = $domain;
        return $this;
    }

    /**
     * 获取路由的域 (不含scheme)
     *
     * @return mixed|null
     */
    public function getDomain()
    {
        return isset($this->action['domain']) ? str_replace(['http', 'https'], '', $this->action['domain']) : null;
    }

    /**
     * 获取路由响应的HTTP方法，如GET, POST, HEAD, PUT, DELETE, OPTION等
     *
     * @return array
     */
    public function methods()
    {
        return $this->methods;
    }

    /**
     * 确定路由是否仅响应HTTP请求。
     *
     * @return bool
     */
    public function httpOnly()
    {
        return in_array('http', $this->action, true);
    }

    /**
     * 确定路由是否仅响应HTTPS请求。
     *
     * @return bool
     */
    public function httpsOnly()
    {
        return $this->secure();
    }

    /**
     * 确定路由是否仅响应HTTPS请求。
     *
     * @return bool
     */
    public function secure()
    {
        return in_array('https', $this->action, true);
    }

    /**
     * request是否与该route匹配
     *
     * @param Request $request
     * @param bool $includeMethodValidator
     * @return bool
     */
    public function matches(Request $request, $includeMethodValidator = true)
    {
        $this->compiledRoute();

        $validators = [
            new UrlValidator,
            new MethodValidator,
            new SchemeValidator
        ];

        foreach ($validators as $validator) {
            if (!$includeMethodValidator && $validator instanceof MethodValidator) {
                continue;
            }

            if (!$validator->matches($this, $request)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 执行route action，并返回response
     *
     * @return mixed
     */
    public function run()
    {
        if ($this->isControllerAction()) {
            $callback = $this->parseControllerCallback();

            return $this->controllerDispatcher()->dispatch(
                $this, $this->getController(), $this->getControllerMethod()
            );
        }

        return $this->runCallable();
    }

    /**
     * Dispatcher
     *
     * @return ControllerDispatcher
     */
    public function controllerDispatcher()
    {
        return new ControllerDispatcher($this->container);
    }

    /**
     * 执行route action，并返回response
     *
     * @return mixed
     */
    public function runCallable()
    {
        $callable = $this->action['uses'];

        return $callable(...array_values($this->resolveMethodDependencies($this->parametersWithoutNulls(), new \ReflectionFunction($callable))));
    }

    /**
     * 移除参数中的空值
     *
     * @return array
     */
    public function parametersWithoutNulls()
    {
        return array_filter($this->parameters(), function ($p) {
            return ! is_null($p);
        });
    }

    /**
     * 尝试获取route的参数，如果未设置，则抛出LogicException
     *
     * @return array|\LogicException
     */
    public function parameters()
    {
        if (isset($this->parameters)) {
            return $this->parameters;
        }

        throw new \LogicException('Route is not bound.');
    }

    public function hasParameters()
    {
        return isset($this->parameters) && !empty($this->parameters);
    }

    /**
     * 判断$name参数是否存在
     *
     * @param $name
     * @return bool
     */
    public function hasParameter($name)
    {
        if ($this->hasParameters()) {
            return array_key_exists($name, $this->parameters());
        }
        return false;
    }

    public function parameter($name, $default = null)
    {
        return Arr::get($this->parameters(), $name, $default);
    }

    /**
     * 给指定的参数$name设置默认值
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function defaults($name, $value)
    {
        $this->defaults[$name] = $value;
        return $this;
    }

    /**
     * 绑定request到路由
     *
     * @param $request
     * @return $this
     */
    public function bind($request)
    {
        $this->compile();
        $this->parameters = $this->buildPathParameters($request);
        return $this;
    }

    /**
     * 构建path参数
     *
     * @example uri := /{foo}/{bar}, request url <= /lotus/beer, 则构建的参数为：['foo' => 'lotus', 'bar' => 'beer']
     * @param $request
     * @return array
     */
    protected function buildPathParameters($request)
    {
        if (empty($paramNames = $this->parameterNames())) {
            return [];
        }

        preg_match($this->compiledRoute()->getRegex(), $request->decodedPath(), $matches);

        $parameters = array_intersect_key(array_slice($matches, 1), array_flip($paramNames));
        return array_filter($parameters, function($v) {
            return is_string($v) && strlen($v) > 0;
        });
    }

    /**
     * 从route uri中获取parameter names
     *
     * @example http://example.com/{foo}/{bar?}?name=lotus : ['foo', 'bar']
     * @example http://example.com/foo/bar?name=lotus : []
     *
     * @return array
     */
    public function parameterNames()
    {
        preg_match_all("/\{(.*?)\}/", $this->getDomain() . $this->uri, $match);

        return array_map(function ($m) { return trim($m, '?'); }, $match[1]);
    }

    /**
     * Compile the route into a Symfony CompiledRoute instance.
     *
     * @return \Symfony\Component\Routing\CompiledRoute
     */
    public function compiledRoute()
    {
        if (! $this->compiled) {
            $this->compiled = $this->compile();
        }

        return $this->compiled;
    }

    /**
     * compile route
     *
     * @return \Symfony\Component\Routing\CompiledRoute
     */
    public function compile()
    {
        $optionals = $this->getOptionalParameters();

        $uri = preg_replace('/\{(\w+?)\?\}/', '{$1}', $this->uri());

        return (
            new SymfonyRoute($uri, $optionals, $this->wheres, ['utf8' => true], $this->getDomain() ?: '')
        )->compile();
    }

    protected function getOptionalParameters()
    {
        preg_match_all('/\{(\w+?)\?\}/', $this->uri(), $matches);

        return isset($matches[1]) ? array_fill_keys($matches[1], null) : [];
    }

    /**
     * 解析action
     *
     * @note action的合法形式如下：
     *      ['http', 'uses' => function() {}],
     *      function () {},
     *      ['http', 'uses' => 'ControllerConcrete@index']
     * @param $action
     * @return array
     */
    protected function parseAction($uri, $action)
    {
        if (is_null($action)) {
            return self::missingAction($uri);
        }

        if (is_callable($action)) {
            return ['uses' => $action];
        } else if (!isset($action['uses'])) {
            $action['uses'] = static::findCallable($action);
        }

        else if (is_string($action['uses']) && ! Str::contains($action['uses'], '@')) {
            $action['uses'] = static::makeInvokable($action['uses']);
        }

        return $action;
    }

    protected static function missingAction($uri)
    {
        return ['uses' => function () use ($uri) {
            throw new LogicException("Route for [{$uri}] has no action.");
        }];
    }

    /**
     * @param $action
     * @return mixed
     */
    protected static function findCallable($action)
    {
        return Arr::first($action, function($value, $key) {
            return is_callable($value) && is_numeric($key);
        });
    }

    /**
     * @param $action
     * @return string
     */
    protected static function makeInvokable($action)
    {
        if (! method_exists($action, '__invoke')) {
            throw new \UnexpectedValueException("Invalid route action: [{$action}]");
        }

        return $action.'@__invoke';
    }

    /**
     * 判断是否为controller action
     * @return bool
     */
    public function isControllerAction()
    {
        return is_string($this->action['uses']);
    }

    /**
     * 解析controller action
     *
     * @return array
     */
    protected function parseControllerCallback()
    {
        return Str::parseCallback($this->action['uses']);
    }

    /**
     * 获取参数
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->parameter($name);
    }
}