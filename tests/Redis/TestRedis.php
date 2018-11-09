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
use Xdp\Container\Container;
use Xdp\Queue\LuaScript;
use Xdp\Redis\RedisManager;

class TestRedis extends TestCase
{
    public function testRedis()
    {
        $driver = "PhpRedis";

        $config = [
            'xdp.bj.test' =>[
                'host'=> '127.0.0.1',
                'port'=> 6379,
                'password' => "foobared"
            ]
        ];

        $redis = (new RedisManager(new Container(), $driver, $config))->connection('xdp.bj.test');

        $test_key = 'liangtaotest:h';

        $redis->hSet($test_key, 'field4', 'value4');

        $res = $redis->del($test_key);

        $this->assertEquals(1, $res);

        $redis->hSet($test_key, 'field1', 'value1');
        $redis->hSet($test_key, 'field2', 'value2');

        $res = $redis->hMGet($test_key, array('field1', 'field2', 'field3'));
        $this->assertCount(2, $res);

        $z_key = 'liangtaotest:z';
        $redis->del($z_key);

        $res = $redis->zAdd($z_key, 1, 'val1');

        $this->assertEquals(1, $res);
        $this->assertEquals(0, $redis->zAdd($z_key, 2, 'val1'));

        $redis->zAdd($z_key, 10, 'val2');
        $redis->zAdd($z_key, 4, 'val3');
        $redis->zAdd($z_key, 3, 'val4');
        $redis->zAdd($z_key, 8, 'val5');

        $res = $redis->zRange($z_key, 0, -1);
        $this->assertCount(5, $res);

        $res = $redis->zRangeByScore($z_key, 0, 10, true, 0, 20);

        $this->assertCount(5, $res);
        $this->assertEquals(['val1'=>2, 'val2'=>10, 'val3'=>4, 'val4'=>3, 'val5'=>8], $res);
        $this->assertEquals(5, $redis->zCount($z_key, 0, 10));
        $this->assertEquals(3, $redis->zCount($z_key, 0, 4));

        $redis->zAdd($z_key, 100, 'val100');
        $this->assertEquals(1, $redis->zRem($z_key, 'val100'));
        $this->assertEquals(0, $redis->zRem($z_key, 'val100'));

        $redis->zAdd($z_key, 100, 'val100');
        $this->assertEquals(1, $redis->zDelete($z_key, 'val100'));
        $this->assertEquals(0, $redis->zDelete($z_key, 'val100'));

        $list_key = 'listkey';
        $list_key_2 = "listkey2";

        $redis->del($list_key);
        $redis->del($list_key_2);

        $redis->lPush($list_key, 'A');
        $redis->lPush($list_key, 'B');
        $redis->lPush($list_key, 'C');
        $redis->lPush($list_key, 'A');
        $redis->lPush($list_key, 'A');

        $len = $redis->eval("return redis.call('llen', KEYS[1])", 1, $list_key);
        //var_dump($len);
        $this->assertEquals(5, $len);

        $this->assertEquals(array('A', 'A', 'C', 'B', 'A'), $redis->lRange($list_key, 0, -1));

        $redis->lRem($list_key, 'A', 2);
        $this->assertEquals(array('C', 'B', 'A'), $redis->lRange($list_key, 0, -1));

        $ele = $redis->rPopLPush($list_key, $list_key_2);
        $this->assertEquals('A', $ele);

        $this->assertEquals(1, $redis->lSize($list_key_2));

        $redis->blPop([$list_key, $list_key_2], 10);

        $redis->blPop([$list_key, $list_key_2], 10);

        $redis->blPop([$list_key_2], 10);

        $redis->blPop([$list_key_2], 10);

        $redis->flushAll();

        $r = $redis->blPop(['test'], 1);
        var_dump($r);
    }
}