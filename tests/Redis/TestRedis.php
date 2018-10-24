<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/22
 * Time: 下午1:24
 */

namespace Xdp\Test\Redis;

require_once __DIR__ . "/../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Xdp\Redis\RedisManager;

class TestRedis extends TestCase
{
    public function testRedis()
    {
        $driver = "PhpRedis";

        $config = [
            'xdp.bj.test' =>[
                'host'=> '10.51.53.235',
                'port'=> 6379,
            ]
        ];

        $redis = (new RedisManager($driver, $config))->connection('xdp.bj.test');

        $res = $redis->delete('h');

        $this->assertCount(0, $res);

        $redis->hSet('h', 'field1', 'value1');
        $redis->hSet('h', 'field2', 'value2');

        $res = $redis->hMGet('h', array('field1', 'field2'));
        $this->assertCount(2, $res);
    }
}