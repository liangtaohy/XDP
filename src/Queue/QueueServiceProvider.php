<?php
/**
 * Queue Service Provider used to register queue service
 *
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/1
 * Time: 5:48 PM
 */

namespace Xdp\Queue;

use Xdp\Contract\Support\ServiceProviderInterface;
use Xdp\Queue\Connectors\RedisConnector;

class QueueServiceProvider implements ServiceProviderInterface
{
    /**
     * IoC Container
     *
     * @var \Xdp\Container\Container
     */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 注册service
     */
    public function register()
    {
        return $this->registerManager();
    }

    protected function registerManager()
    {
        $this->app->instance('queue', function ($app) {
            return tap(new QueueManager($app), function ($manager) {
                $this->registerConnectors($manager);
            });
        });

        return $this;
    }

    protected function registerConnectors($manager)
    {
        $this->registerRedisConnector($manager);
    }

    protected function registerRedisConnector($manager)
    {
        $manager->addConnector('Redis', function () use ($manager) {
            return new RedisConnector($this->app['redis']);
        });
    }
}