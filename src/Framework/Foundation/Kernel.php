<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/29
 * Time: 下午5:30
 */
namespace Xdp\Framework\Foundation;

use Mockery\Exception;
use Xdp\Contract\Foundation\Kernel as KernelContract;
use Xdp\Container\Container;
use Xdp\Pipeline\Pipeline;
use Xdp\Routing\Router;
use Xdp\Http\Request;

class Kernel implements KernelContract
{
    /**
     * Application Instance
     *
     * @var Container
     */
    protected $app;

    /**
     * Router Instance
     *
     * @var Router
     */
    protected $router;

    protected $middleware;

    /**
     * Kernel constructor.
     *
     * @param Container $container
     * @param Router $router
     */
    public function __construct(Container $container, Router $router)
    {
        $this->app = $container;
        $this->router = $router;
    }

    /**
     * Application bootstrap for http request
     *
     * @return mixed
     */
    public function bootstrap()
    {
    }

    /**
     * 处理http request
     *
     * @param \Xdp\Http\Request $request
     * @return \Xdp\Http\Response
     */
    public function handle($request)
    {
        $this->app->instance('request', $request);

        $this->bootstrap();

        try {
            $this->sendRequestThroughRouter($request);
        } catch (Exception $e) {
            // 如果抛出异常，则需要把异常信息返回给请求方
        }
    }

    /**
     * dispatch request to router
     *
     * @param \Xdp\Http\Request $request
     * @return \Xdp\Http\Response
     */
    public function sendRequestThroughRouter($request)
    {
        return (new Pipeline($this->app))->send($request)
                ->through($this->middleware)
                ->then($this->dispatchToRouter());
    }

    /**
     * Get A Closure For dispatch
     *
     * @return \Closure
     */
    public function dispatchToRouter()
    {
        return function ($request) {
            $this->app->instance('request', $request); // 更新request到application container

            return $this->router->dispatch($request);
        };
    }

    /**
     * 中止应用
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return mixed
     */
    public function terminate($request, $response)
    {
        // empty
    }

    /**
     * 返回Application实例
     *
     * @return \Xdp\Contract\Container\ContainerInterface|\Xdp\Container\Container|\Xdp\Framework\Application
     */
    public function getApplication()
    {
        return $this->app;
    }
}