<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/21
 * Time: 下午10:19
 */
namespace Xdp\Redis\Connection;

use Redis;
use RedisException;

class PhpRedisConnection
{
    /**
     * Phpredis client
     *
     * @var \Redis
     */
    private $client;

    public $errmsg;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Get the value related to the specified key
     *
     * @note https://github.com/phpredis/phpredis/#get
     * @param string $key
     * @return null|string
     */
    public function get($key)
    {
        $v = $this->client->get($key);
        return $key !== false ? $v : null;
    }

    /**
     * Set the string value in argument as value of the key. If you're using Redis >= 2.6.12, you can pass extended options as explained below
     *
     * @param $key
     * @param $value string value
     * @param null $options timeout or an options array
     * @return bool
     */
    public function set($key, $value, $options = null)
    {
        return $this->client->set($key, $value, $options);
    }

    /**
     * Set the string value in argument as value of the key, with a time to live. PSETEX uses a TTL in milliseconds.
     *
     * @param $key
     * @param $ttl
     * @param $value
     * @return bool
     */
    public function setex($key, $ttl, $value)
    {
        return $this->client->setex($key, $ttl, $value);
    }

    /**
     * 如果redis中不存在该键key，则将参数中的字符串值$value设置为键的值。
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function setnx($key, $value)
    {
        return $this->client->setnx($key, $value);
    }

    /**
     * 检查Key是否存在
     *
     * @param $redisKey
     * @return bool|int
     */
    public function existsKey($redisKey)
    {
        return $this->client->exists($redisKey);
    }

    /**
     * 自增
     *
     * @param $key
     * @param int $step
     * @return bool|int 返回新值或false
     */
    public function incrBy($key, $step = 1)
    {
        $type = gettype($step);

        if ($type == 'integer') {
            return $this->command('incrBy', [$key, $step]);
        } elseif ($type == 'double') {
            return $this->command('incrByFloat', [$key, $step]);
        }
        return false;
    }

    /**
     * 自减
     *
     * @param $key
     * @param int $step
     * @return bool
     */
    public function decrBy($key, $step = 1)
    {
        $type = gettype($step);

        if ($type == 'integer') {
            return $this->command('decrBy', [$key, $step]);
        } elseif ($type == 'double') {
            return $this->command('incrBy', [$key, -1 * $step]);
        }

        return false;
    }

    /**
     * 获取给定keys的所有值，不存在的值设定为null
     *
     * @param array $keys
     * @return array
     */
    public function mGet(array $keys)
    {
        return array_map(function ($v) { return $v !== false ? $v : null; }, $this->client->mget($keys));
    }

    public function hMGet($key, array $fields)
    {
        return $this->command('hMGet', [$key, $fields]);
    }

    protected function command($method, array $parameters = [])
    {
        $res = false;
        try {
            $res = $this->client->{$method}(...$parameters);
        } catch (RedisException $e) {
            $p = json_encode($parameters);
            $this->errmsg = "redis_error: redis is down or overload cmd[{$method}] parameters[{$p}] " .
                " errno[{$e->getCode()}] errmsg[{$e->getMessage()}]";
        }

        return $res;
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->command($method, $parameters);
    }
}