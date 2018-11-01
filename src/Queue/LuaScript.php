<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/26
 * Time: 下午6:24
 */

namespace Xdp\Queue;


class LuaScript
{
    /**
     * 获取计算queue队列大小的Lua script
     *
     * KEYS[1] - running queue name
     * KEYS[2] - delayed queue name
     * KEY[3] - reserved queue name
     *
     * @return string
     */
    public static function size()
    {
        return <<<'LUA'
return redis.call('llen', KEYS[1]) + redis.call('zcard', KEYS[2]) + redis.call('zcard', KEYS[3])
LUA;
    }
}