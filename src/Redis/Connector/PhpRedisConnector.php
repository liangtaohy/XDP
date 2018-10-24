<?php
/**
 * Phpredis module client
 *
 * Note: https://github.com/phpredis/phpredis/#readme
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/20
 * Time: 下午11:07
 */
namespace Xdp\Redis\Connector;

use Xdp\Redis\Connection\PhpRedisConnection;

use Redis;

class PhpRedisConnector
{
    /**
     * 建立redis连接
     *
     * @param $config
     * @return \Xdp\Redis\Connection\PhpRedisConnection
     * @throws \RedisException
     */
    public function connect($config)
    {
        return new PhpRedisConnection($this->redisClient($config));
    }

    protected function redisClient($config)
    {
        // create client
        $client = new Redis();

        // connect
        if ($client->{($config['persistent'] ?? false) ? 'pconnect' : 'connect'}(
                $config['host'], $config['port'], $config['timeout'] ?? 0
            ) === false) {
            throw new \RedisException("can't connect to redis, %s:%d", $config['host'], $config['port']);
        }

        // try auth if needed
        if (!empty($config['password']) && $client->auth($config['password']) === false) {
            throw new \RedisException("auth failed, %s:%d", $config['host'], $config['port']);
        }

        // select to indexed db if specified
        if (!empty($config['db'])) {
            $client->select($config['db']);
        }

        // slow query
        if (!empty($config['read_timeout'])) {
            $client->setOption(\Redis::OPT_READ_TIMEOUT, $config['read_timeout']);
        }

        return $client;
    }
}