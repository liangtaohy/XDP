<?php
/**
 * Created by PhpStorm.
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/6
 * Time: 10:55 AM
 */

return [
    'driver'    => 'PhpRedis',
    'bj.redis.01'    => [
        'host'  => '127.0.0.1',
        'port'  => 6379,
        'persistent'    => true,
        'password'  => 'foobared',
        'db'    => null,
        'read_timeout'  => 10,
    ],
];