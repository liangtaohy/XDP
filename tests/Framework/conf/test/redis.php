<?php
/**
 * Created by PhpStorm.
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/2
 * Time: 9:45 AM
 */

return [
    'driver'    => 'PhpRedis',
    'bj.redis.01'    => [
        'host'  => '127.0.0.1',
        'port'  => 6376,
        'persistent'    => true,
        'password'  => null,
        'db'    => null,
        'read_timeout'  => 10,
    ],
];