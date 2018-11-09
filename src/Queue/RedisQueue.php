<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/20
 * Time: 下午5:01
 */

namespace Xdp\Queue;

use Xdp\Contract\Queue\Queue as QueueInterface;

use JsonException;

/**
 * Class RedisQueue
 *
 * @note redis queue实现, 假设队列名称为queue
 * @note 支持三种队列：queue(read, queue:delay, queue:reserved
 *
 * @package Xdp\Queue
 */
class RedisQueue extends Queue implements QueueInterface
{
    const APPENDIX_DELAY        = ':delayed'; // 延迟队列
    const APPENDIX_RESERVED     = ':reserved'; // 保留队列
    const APPENDIX_DONE         = ':done'; // 已完成队列

    /**
     * @var \Xdp\Redis\RedisManager
     */
    protected $redis;

    /**
     * 默认队列名称
     *
     * @var string
     */
    protected $default;

    /**
     * 队列blPop操作的最大阻塞时间
     *
     * @var int
     */
    public $timeout;

    /**
     * RedisQueue constructor.
     * @param $redis
     * @param string $connection_name
     * @param int $timeout
     * @param int $retryAfter
     * @param string $default
     */
    public function __construct($redis, string $connection_name, int $timeout, int $retryAfter, $default = 'default')
    {
        $this->redis = $redis;
        $this->default = $default;
        $this->connection_name = $connection_name;
        $this->timeout = $timeout;
        $this->retryAfter = $retryAfter;
    }

    /**
     * 设置超时时间
     *
     * @param int $timeout
     * @return $this
     */
    public function setTimeOut(int $timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * 返回超时时间
     *
     * @return int
     */
    public function getTimeOut()
    {
        return $this->timeout;
    }

    /**
     * 将数据添加到队列$queue
     *
     * @param $queue
     * @param string $event
     * @return bool|int
     */
    public function pushOn($queue, $event)
    {
        return $this->push($event, $queue);
    }

    /**
     * @param $event
     * @param null $queue
     * @return bool|int
     */
    public function push($event, $queue = null)
    {
        return $this->pushRaw($this->createPayload($event), $queue);
    }

    /**
     * 将数据添加到队列$queue
     *
     * @param $payload
     * @param null $queue
     * @return bool|int
     */
    public function pushRaw($payload, $queue = null)
    {
        return $this->connection()->rPush($this->getQueueName($queue), $payload);
    }

    /**
     * 将数据添加到delay队列
     *
     * @param string $queue 队列名称
     * @param mixed $event
     * @param int $delay 延迟到某个时间执行, 毫秒
     * @return bool|int
     */
    public function laterOn($queue, $event, $delay = 0)
    {
        return $this->later($event, $delay, $queue);
    }

    /**
     * @param $event
     * @param int $delay
     * @param null $queue
     * @return int
     */
    public function later($event, $delay = 0, $queue = null)
    {
        return $this->lateRaw($this->createPayload($event), $delay, $queue);
    }

    /**
     * 将数据添加到delay队列
     *
     * @param $payload
     * @param int $delay
     * @param null $queue
     * @return int
     */
    public function lateRaw($payload, $delay = 0, $queue = null)
    {
        return $this->connection()->zAdd($this->getQueueName($queue)  . self::APPENDIX_DELAY, $delay, $payload);
    }

    /**
     * 求队列的长度（running、delayed、reserved）
     *
     * @param null $queue
     * @return mixed
     * @throws \Exception
     */
    public function size($queue = null)
    {
        $queue = $this->getQueueName($queue);

        return $this->connection()->eval(
            LuaScript::size(), 3, $queue, $queue, $queue . self::APPENDIX_DELAY, $queue . self::APPENDIX_RESERVED
        );
    }

    /**
     * @param null $queue
     * @return array
     * @throws JsonException
     */
    public function pop($queue = null)
    {
        $queue = $queue ?? $this->getQueueName($queue);

        $this->migrate($this->getQueueName($queue));

        return $this->retrieveNextEvent($queue);
    }

    /**
     * @param $queue
     * @return array
     * @throws JsonException
     */
    public function retrieveNextEvent($queue)
    {
        $item = $this->connection()->blPop([$queue], $this->timeout);

        if (empty($item)) {
            return [null, null];
        }

        try {
            $payload = json_decode($item[1], true);
            $payload['attempts']++;
            $reserved = json_encode($payload);
            if (!is_null($this->retryAfter)) {
                $this->connection()->zAdd($queue . self::APPENDIX_RESERVED, getMicroTime() + $this->retryAfter * 1000, $reserved);
            }
        } catch (JsonException $e) {
            echo $e->getMessage() . PHP_EOL;
            return [null, null];
        }

        return [$item[1], $reserved];
    }

    /**
     * 删除队列元素
     *
     * @param $rawdata
     * @param string $queue
     * @return int
     */
    public function delete($rawdata, $queue = '')
    {
        if ($this->retryAfter) {
            $this->connection()->zDelete($this->getQueueName($queue) . self::APPENDIX_RESERVED, $rawdata);
        }
    }

    /**
     * @param string $queue
     * @throws \Exception
     */
    protected function migrate(string $queue)
    {
        $this->migrateExpiredJobs($queue . self::APPENDIX_DELAY, $queue);

        if (!is_null($this->retryAfter)) {
            $this->migrateExpiredJobs($queue . self::APPENDIX_RESERVED, $queue);
        }
    }

    /**
     * @param string $from
     * @param string $to
     * @return mixed
     * @throws \Exception
     */
    protected function migrateExpiredJobs(string $from, string $to)
    {
        return $this->connection()->eval(
            LuaScript::migrateExpiredJobs(), 2, $from, $to, getMicroTime()
        );
    }

    /**
     * 获取一条redis连接
     *
     * @return \Xdp\Redis\Connection\PhpRedisConnection
     */
    protected function connection()
    {
        return $this->redis->connection($this->getConnectionName() ?? $this->default);
    }

    /**
     * 获取队列名称
     *
     * @param string $queue
     * @return string
     */
    public function getQueueName($queue = '')
    {
        return empty($queue) ? $this->default : $queue;
    }
}