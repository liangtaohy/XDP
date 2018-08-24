<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/24
 * Time: 下午1:16
 */

namespace Xdp\Contract\Routing;

use Xdp\Routing\Route;

interface ControllerDispatcher
{
    /**
     * 把请求分发给指定的controller和方法method
     *
     * @param  Xdp\Routing\Route  $route
     * @param  mixed  $controller
     * @param  string  $method
     * @return mixed
     */
    public function dispatch(Route $route, $controller, $method);

    /**
     * 获取controller的中间件
     *
     * @param  Xdp\Routing\Controller  $controller
     * @param  string  $method
     * @return array
     */
    public function getMiddleware($controller, $method);
}