<?php
/**
 * 队列管理器，用于维护或新建队列
 *
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/1
 * Time: 11:29 AM
 */

namespace Xdp\Queue;


use Xdp\Queue\Connectors\RedisConnector;
use Xdp\Redis\RedisManager;

class QueueManager
{
    /**
     * IoC容器
     * @var \Xdp\Contract\Container\ContainerInterface
     */
    public $app;

    /**
     * connector数组
     *
     * @var array
     */
    public $connectors  = [];

    public $connections = [];

    public $config;

    public function __construct($app)
    {
        $this->app = $app;
        $this->config = $app['config']['queue'];
    }

    public function addConnector($driver, \Closure $closure)
    {
        $this->connectors[$driver] = $closure;
        return $this;
    }

    public function getConnector($driver)
    {
        if (is_null($this->connectors[$driver])) {
            throw new \RuntimeException("invalid connector driver[$driver] for queue");
        }
        return call_user_func($this->connectors[$driver]);
    }

    /**
     * 返回一个队列连接
     *
     * @param string $name 连接名称，默认使用配置文件的
     * @return mixed
     */
    public function connection($name = null)
    {
        $name = $name ?? $this->config['connection'];

        if (isset($this->connections[$name])) {
            return $this->connections[$name];
        }

        $this->connections[$name] = $this->getConnector($this->config['driver'])->connect($this->config);
        $this->connections[$name]->setContainer($this->app)->setConnectionName($name);

        return $this->connections[$name];
    }
}