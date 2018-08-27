<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/24
 * Time: 下午3:09
 */

namespace Xdp\Routing;

use Closure;
use Xdp\Utils\Arr;
use Xdp\Utils\Str;
use Symfony\Component\Routing\Route as SymfonyRoute;

class Route
{
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
     * @var
     */
    public $controller;

    /**
     * 路由参数
     *
     * @var
     */
    public $parameters;

    /**
     * 容器实例
     *
     * @var
     */
    public $container;

    /**
     * compiled route
     *
     * @var
     */
    public $compiled;

    /**
     * Route constructor.
     *
     * @param $methods
     * @param $uri
     * @param $action
     */
    public function __construct($methods, $uri, array $action = [])
    {
        $this->uri = $uri;
        $this->methods = (array) $methods;

        $this->action = $this->parseAction($action);
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

    public function runController()
    {
        if ($this->isControllerAction()) {
            $callback = $this->parseControllerCallback();

            $this->controllerDispatcher()->dispatch(
                $this, $callback[0], $callback[1]
            );
        }

        return $this->runCallable();
    }

    /**
     * Compile the route into a Symfony CompiledRoute instance.
     *
     * @return SymfonyRoute
     */
    protected function compileRoute()
    {
        if (! $this->compiled) {
            $this->compiled = $this->compile();
        }

        return $this->compiled;
    }

    /**
     * compile route
     *
     * @return SymfonyRoute
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
        preg_match_all('/\{(\w+?)\?\}/', $this->route->uri(), $matches);

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
    protected function parseAction($action)
    {
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

    protected static function findCallable($action)
    {
        return Arr::first($action, function($value, $key) {
            return is_callable($value) && is_numeric($key);
        });
    }

    protected static function makeInvokable($action)
    {
        if (! method_exists($action, '__invoke')) {
            throw new \UnexpectedValueException("Invalid route action: [{$action}]");
        }

        return $action.'@__invoke';
    }

    protected function isControllerAction()
    {
        return is_string($this->action['uses']);
    }

    protected function parseControllerCallback()
    {
        return Str::parseCallback($this->action['uses']);
    }
}