<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/20
 * Time: 下午5:01
 */

namespace Xdp\Queue;

use Xdp\Contract\Queue\Queue as QueueInterface;

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
    const APPENDIX_DELAY        = ':delayed';
    const APPENDIX_RESERVED     = ':reserved';
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
     * RedisQueue constructor.
     *
     * @param $redis Redis Manager
     * @param string $connection_name 连接名称
     * @param string $default 默认的队列名称
     */
    public function __construct($redis, $connection_name, $default = 'default')
    {
        $this->redis = $redis;
        $this->default = $default;
        $this->connection_name = $connection_name;
    }

    /**
     * 将数据添加到队列$queue
     *
     * @param $queue
     * @param string $job
     * @param null $data
     * @return bool|int
     */
    public function pushOn($queue, $job, $data = null)
    {
        return $this->push($this->createPayload($job, $data), $queue);
    }

    /**
     * 将数据添加到队列$queue
     *
     * @param $payload
     * @param null $queue
     * @return bool|int
     */
    public function push($payload, $queue = null)
    {
        return $this->connection()->rPush($this->getQueueName($queue), $payload);
    }

    /**
     * 将数据添加到delay队列
     *
     * @param string $queue 队列名称
     * @param mixed $job 作业
     * @param null $data 数据
     * @param int $delay 延迟到某个时间执行, 毫秒
     * @return bool|int
     */
    public function lateOn($queue, $job, $data = null, $delay = 0)
    {
        return $this->late($this->createPayload($job, $data), $delay, $this->getQueueName($queue));
    }

    /**
     * 将数据添加到delay队列
     *
     * @param $payload
     * @param int $delay
     * @param null $queue
     * @return int
     */
    public function late($payload, $delay = 0, $queue = null)
    {
        return $this->connection()->zAdd($this->getQueueName($queue)  . self::APPENDIX_DELAY, $delay, $payload);
    }

    /**
     * 求队列的长度（running、delayed、reserved）
     *
     * @param string $queue
     * @return mixed
     */
    public function size($queue = '')
    {
        $queue = $this->getQueueName($queue);

        return $this->connection()->eval(
            LuaScript::size(), 3, $queue, $queue, $queue . self::APPENDIX_DELAY, $queue . self::APPENDIX_RESERVED
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