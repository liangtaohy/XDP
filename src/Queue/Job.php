<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/19
 * Time: 下午3:21
 */
namespace Xdp\Queue;

use Xdp\Contract\Support\Jsonable;

class Job implements \JsonSerializable
{
    /**
     * IoC container
     *
     * @var
     */
    public $container;

    /**
     * Job唯一标识
     *
     * @var
     */
    public $jobId;

    /**
     * Job名称
     *
     * @var int
     */
    public $jobName;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_at;

    /**
     * 延迟时间，秒
     * @var int
     */
    public $delay;

    /**
     * 延迟到，时间戳（秒）
     *
     * @var int
     */
    public $delay_at;

    /**
     * 是否已删除
     *
     * @var bool
     */
    public $deleted;

    /**
     * 删除时间戳, 时间戳（毫秒）
     *
     * @var int
     */
    public $deleted_at;

    /**
     * 过期时间戳
     *
     * @var int
     */
    public $expire;

    /**
     * 当前重试次数
     *
     * @var int
     */
    public $attempts;

    /**
     * 最大重试次数
     *
     * @var int
     */
    public $max_tries;

    /**
     * @var string
     */
    public $rawdata;

    /**
     * Job处理器
     *
     * @var string
     */
    public $handler;

    public function delete()
    {
        $this->deleted = true;
        $this->deleted_at = microTime();
    }

    public function getJobId()
    {
        return $this->jobId;
    }

    public function getJobName()
    {
        return $this->jobName;
    }

    /**
     * @param string $handler
     */
    public function setHandler(string $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'jobId'         => $this->getJobId(),
            'jobName'       => $this->getJobName(),
            'create_at'     => $this->create_at,
            'deleted'       => $this->deleted ?? null,
            'deleted_at'    => $this->deleted_at ?? null,
            'delay'         => $this->delay ?? null,
            'attempts'      => $this->attempts ?? null,
            'max_tries'     => $this->max_tries ?? null,
            'expire'        => $this->expire ?? 0,
            'handler'       => $this->handler
        ];
    }
}