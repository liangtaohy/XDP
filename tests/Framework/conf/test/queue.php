<?php
/**
 * Queue Config Case
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/2
 * Time: 9:27 AM
 */

return [
    'driver'    => 'Redis',
    'connection'    => "bj.php.01",
    'timeout'   => 10, // 10 seconds
    'retryAfter'    => 60, // 60 seconds
    'default'   => 'lotushy', // default queue name
];