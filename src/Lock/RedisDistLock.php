<?php
/**
 *
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2019/3/29
 * Time: 7:01 PM
 */

namespace Xdp\Lock;
use RedisException;
use Xdp\Contract\Lock\IDistLock;

class RedisDistLock implements IDistLock
{
    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function lock($key, $token, $expire = 3000)
    {
        try {
            return $this->redis->set($key, $token, ['NX', 'EX' => $expire]);
        } catch (RedisException $e) {
            throw new \Exception(sprintf("Failed to acqurie lock %s with token %s", $key, $token), 0, $e);
        }
    }

    public function unlock($key, $token)
    {
        $script = <<< 'LUA'
if redis.call("get",KEYS[1]) == ARGV[1] then
    return redis.call("del",KEYS[1])
else
    return 0
LUA;

        try {
            return $this->redis->eval($script, 1, $key, $token);
        } catch (RedisException $e) {
            throw new \Exception(sprintf("Failed to release lock %s with token %s", $key, $token), 0, $e);
        }
    }
}