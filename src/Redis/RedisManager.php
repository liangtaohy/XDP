<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/20
 * Time: 下午11:06
 */
namespace Xdp\Redis;

use Xdp\Contract\Redis\Factory;

class RedisManager implements Factory
{
    /**
     * Redis Connector
     *
     * @var
     */
    private $driver;

    /**
     * Config
     *
     * @var
     */
    private $config;

    private $connections = [];

    private $app;

    public function __construct($app, $driver, array $config = null)
    {
        $this->app = $app;
        $this->driver = $driver;
        $this->config = $config;
    }

    public function setContainer($container)
    {
        $this->app = $container;
        return $this;
    }

    public function getContainer()
    {
        return $this->app;
    }

    /**
     * 获取连接
     *
     * @param null $name
     * @return \Xdp\Redis\Connection\PhpRedisConnection
     */
    public function connection($name = null)
    {
        $name = $name ?: 'default';

        if (isset($this->connections[$name])) {
            return $this->connections[$name];
        }

        return $this->connections[$name] = $this->resolve($name);
    }

    /**
     * @param null $name
     * @return mixed
     */
    protected function resolve($name = null)
    {
        $name = $name ?: 'default';

        if (isset($this->config[$name])) {
            return $this->connector()->connect($this->config[$name]);
        }

        throw new \InvalidArgumentException("Redis connection [{$name}] not configured.");
    }

    /**
     * @return mixed
     */
    protected function connector()
    {
        $class = __NAMESPACE__ . '\\Connector\\' . $this->driver . "Connector";
        return new $class();
    }
}