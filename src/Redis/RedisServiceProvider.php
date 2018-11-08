<?php
/**
 * Created by PhpStorm.
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/2
 * Time: 10:29 AM
 */

namespace Xdp\Redis;

use Xdp\Contract\Support\ServiceProviderInterface;

class RedisServiceProvider implements ServiceProviderInterface
{
    /**
     * @var \Xdp\Container\Container
     */
    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register()
    {
        $this->app->instance('redis', function ($app) {
            return new RedisManager($app, $app['config']['redis']['driver'], $app['config']['redis']);
        });
    }
}