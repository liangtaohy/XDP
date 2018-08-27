<?php
/**
 * Created by PhpStorm.
 * User: xlegal
 * Date: 2018/8/23
 * Time: 下午4:09
 */

namespace Xdp\Framework;

use Mockery\Exception;
use Xdp\Contract\Kernel as KernelContract;
use Xdp\Pipeline\Pipeline;

class Kernel implements KernelContract
{
    protected $app;

    protected $router;

    protected $middleware = [];

    protected $routeMiddleware = [];

    public function __construct($app, $router)
    {
        $this->app = $app;
        $this->router = $router;
    }

    public function handle($request)
    {
        try {
            /**
             * NOT Safe
             */
            //$request->enableHttpMethodParameterOverride();

            $response = $this->sendRequestThroughRouter($request);
        } catch (Exception $e) {
            $this->reportException($e);

            $response = $this->renderException($request, $e);
        } catch (\Throwable $e) {
            $this->reportException($e = new FatalThrowableError($e));

            $response = $this->renderException($request, $e);
        }

        return $response;
    }

    public function sendRequestThroughRouter($request)
    {
        $this->app->instance('request', $request);
        return (new Pipeline($this->app))
                ->send($request)
                ->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware)
                ->then($this->dispatchToRouter());
    }

    /**
     * 获取Router，并将请求路由给Router
     *
     * @return \Closure
     */
    protected function dispatchToRouter()
    {
        return function ($request) {
            $this->app->instance('request', $request);

            return $this->router->dispatch($request);
        };
    }
}