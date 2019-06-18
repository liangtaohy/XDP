<?php
/**
 * Created by PhpStorm.
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/6
 * Time: 10:34 AM
 */

namespace Xdp\Test\Queue;

require_once __DIR__ . "/../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Xdp\Config\Config;
use Xdp\Container\Container;
use Xdp\Contract\Queue\EventHandler;
use Xdp\Queue\Event;
use Xdp\Queue\LuaScript;
use Xdp\Queue\QueueServiceProvider;
use Xdp\Redis\RedisServiceProvider;
use Xdp\Utils\Str;

class TestRedisQueue extends TestCase
{
    public function testEvent()
    {
        $e = new Event(new Container());
        $e->setName('test')
            ->setDelay(100)
            ->setRetryAfter(60)
            ->setMaxRetries(10)
            ->setData('hello,world');
        $a = $e->toArray();
        $this->assertEquals($e->getName(), $a['name']);
        $this->assertEquals($e->getData(), $a['data']);
    }

    public function bootstrap($app)
    {
        (new RedisServiceProvider($app))->register();
        (new QueueServiceProvider($app))->register();
    }

    public function testRedisQueue()
    {
        $t = getMicroTime();
        echo PHP_EOL . 'microtime: ' . $t . PHP_EOL;
        $app = new Container();
        $app->instance("config", $config = new Config());

        $config->set('redis', require "../conf/redis.php");
        $config->set('queue', require "../conf/queue.php");

        $this->bootstrap($app);

        $queue = $app['queue'];

        $conn = $queue->connection();
        $e = new Event($app);
        $e->setName('test')
            ->setDelay(100)
            ->setRetryAfter(60)
            ->setMaxRetries(10)
            ->setData('hello, ' . Str::random(5))
            ->setHandler("Xdp\Test\Queue\EventHandlerHub@dump");
        $r = $conn->push($e);
        $this->assertTrue($r > 0);

        // handle event begin
        $ev = $conn->pop();

        $this->assertEquals($e->getName(), json_decode($ev[0], true)['name']);
        $worker = new EventWorkerStub($app);
        $result = $worker->process($ev[0]);
        // handle event end
        $this->assertEquals($e->getData(), $result);

        $e = new Event($app);
        $e->setName('get')
            ->setDelay(8)
            ->setRetryAfter(10)
            ->setMaxRetries(10)
            ->setData('hello, ' . Str::random(5))
            ->setHandler("Xdp\Test\Queue\EventHandlerHub@dump");

        $r = $conn->later($e, $e->getDelayAt());

        $this->assertTrue($r > 0);

        sleep(11);

        try {
            $ev = $conn->pop();
        } catch (\RedisException $e) {
            echo PHP_EOL . $e->getCode() . ':' . $e->getMessage() . PHP_EOL;
        }

        $conn->pop();
        $this->assertEquals($e->getName(), json_decode($ev[0], true)['name']);

        $conn->delete($ev[1]); // 处理完后，清除事件
    }
}

class EventWorkerStub
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * fire
     *
     * @param mixed $app
     * @param string $payload
     * @return mixed
     */
    public function process($payload)
    {
        $e = Event::load($this->app, $payload);
        return $e->fire();
    }
}

class EventHandlerHub implements EventHandler
{
    public function dump($event, $payload)
    {
        echo PHP_EOL . $event->getId() . PHP_EOL;
        echo PHP_EOL . "handle event with data >>> payload: " . $payload . PHP_EOL;
        return $payload;
    }

    public function handle($event, $payload)
    {
        echo PHP_EOL . "The Default Handle Method >>> payload: " . $payload . PHP_EOL;
        return $payload;
    }
}