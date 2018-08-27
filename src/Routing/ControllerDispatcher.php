<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/24
 * Time: 下午3:02
 */

namespace Xdp\Routing;

class ControllerDispatcher implements \Xdp\Contract\Routing\ControllerDispatcher
{
    /**
     * 把请求分发给指定的controller和方法method
     *
     * @param  Xdp\Routing\Route  $route
     * @param  \Xdp\Contract\Routing\Controller  $controller
     * @param  string  $method
     * @return mixed
     */
    public function dispatch(Route $route, $controller, $method)
    {
        $parameters = [];

        if (method_exists($controller, 'callAction')) {
            return $controller->callAction($method, $parameters);
        }

        return $controller->{$method}(...array_values($parameters));
    }

    /**
     * 获取controller的中间件
     *
     * @param  Xdp\Routing\Controller  $controller
     * @param  string  $method
     * @return array
     */
    public function getMiddleware($controller, $method)
    {

    }
}