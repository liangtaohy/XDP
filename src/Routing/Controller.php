<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/24
 * Time: 下午1:07
 */
namespace Xdp\Routing;

use BadMethodCallException;
use Xdp\Contract\Routing\Controller as ControllerContract;

class Controller implements ControllerContract
{
    /**
     * 中间件数组
     * @var array
     */
    protected $middleware = [];

    /**
     * 注册中间件
     *
     * @param  array|string|\Closure  $middleware
     * @param  array   $options
     * @return $this
     */
    public function middleware($middleware, array $options = [])
    {
        foreach ((array)$middleware as $m)
        {
            $this->middleware[] = [
                'middleware' => $m,
                'options' => $options
            ];
        }

        return $this;
    }

    /**
     * 获取controller的中间件
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * 执行controller的Action
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
    }

    /**
     * 处理控制器上的方法不存在时，抛出异常
     *
     * @param $method
     * @param $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}