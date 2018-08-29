<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/29
 * Time: 下午5:21
 */
namespace Xdp\Contract\Foundation;

interface Kernel
{
    /**
     * Application bootstrap for http request
     *
     * @param $app
     * @return mixed
     */
    public function bootstrap();

    /**
     * 处理http request
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request);

    /**
     * 中止应用
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return mixed
     */
    public function terminate($request, $response);

    /**
     * 返回Application实例
     *
     * @return \Xdp\Contract\Container\ContainerInterface|\Xdp\Container\Container|\Xdp\Framework\Application
     */
    public function getApplication();
}