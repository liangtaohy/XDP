<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/20
 * Time: 下午5:01
 */

namespace Xdp\Queue;

use Xdp\Contract\Queue\Queue as QueueInterface;

class RedisQueue extends Queue implements QueueInterface
{
    /**
     * @var \Xdp\Redis\RedisManager
     */
    protected $redis;
    protected $default;

    public function __construct($redis, $default = 'default')
    {
        $this->redis = $redis;
        $this->default = $default;
    }

    public function push($queue, string $job, $data = null)
    {
        return $this->connection()->rPush($queue, $this->createPayload($job, $data));
    }

    public function later($queue, $job, $data = null, $delay = 0)
    {
    }

    public function size($queue)
    {
    }

    protected function connection()
    {
        return $this->redis->connection($this->connection_name ?? $this->default);
    }

    protected function createPayload($job, $data)
    {
        $payload = '';
        return $payload;
    }
}