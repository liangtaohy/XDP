<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/24
 * Time: 下午1:05
 */

namespace Xdp\Contract\Routing;

interface Controller
{
    /**
     * Register middleware on the controller.
     *
     * @param  array|string|\Closure  $middleware
     * @param  array   $options
     * @return
     */
    public function middleware($middleware, array $options = []);

    /**
     * Get the middleware assigned to the controller.
     *
     * @return array
     */
    public function getMiddleware();

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters);
}