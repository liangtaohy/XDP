<?php
/**
 * Redis Connector
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/20
 * Time: 下午5:05
 */

namespace Xdp\Queue\Connectors;

use Xdp\Contract\Redis\Factory as XdpRedis;
use Xdp\Queue\RedisQueue;

class RedisConnector implements ConnectorInterface
{
    protected $redis;
    protected $connection;

    public function __construct(XdpRedis $redis, $connection = null)
    {
        $this->redis = $redis;
        $this->connection = $connection;
    }

    /**
     * 连接到queue
     *
     * @param array $config
     * @return mixed
     */
    public function connect(array $config)
    {
        return new RedisQueue($this->redis,
            $config['connection'] ?? $this->connection,
            $config['timeout'],
            $config['retryAfter'],
            $config['default']);
    }
}