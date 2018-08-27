<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/27
 * Time: 上午11:20
 */

namespace Xdp\Contract\Routing;


interface Registrar
{
    /**
     * 注册一个GET路由到router
     *
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Xdp\Routing\Route
     */
    public function get($uri, $action);

    /**
     * 注册一个POST路由到router
     *
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Xdp\Routing\Route
     */
    public function post($uri, $action);

    /**
     * 注册一个PUT路由到router
     *
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Xdp\Routing\Route
     */
    public function put($uri, $action);

    /**
     * 注册一个DELETE路由到router
     *
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Xdp\Routing\Route
     */
    public function delete($uri, $action);

    /**
     * 注册一个PATCH路由到router
     *
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Xdp\Routing\Route
     */
    public function patch($uri, $action);

    /**
     * 注册一个OPTIONS路由到router
     *
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Xdp\Routing\Route
     */
    public function options($uri, $action);


    /**
     * 注册一个新路由
     *
     * @param array|string $methods
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Xdp\Routing\Route
     */
    public function match($methods, $uri, $action);
    //public function resource($name, $controller, array $options = []);
}