<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/19
 * Time: 下午3:21
 */
namespace Xdp\Queue;

abstract class Job
{
    /**
     * IoC container
     *
     * @var
     */
    public $container;

    /**
     * 当前重试次数
     *
     * @var int
     */
    public $attempts = 0;

    /**
     * 最大重试次数
     *
     * @var int
     */
    public $max_tries = 0;

    /**
     * 任务类型
     *
     * @note 保留字段
     * @var
     */
    public $type = 0;

    /**
     * 连接名称
     *
     * @var string
     */
    public $connection_name = '';

    /**
     * 队列名称
     *
     * @var string
     */
    public $queue = '';

    /**
     * 延迟毫秒数
     *
     * @var int
     */
    public $delay = 0;

    /**
     * 延迟到什么时间开始执行
     *
     * @note $this->delay_at = now() + $delay
     * @var int
     */
    public $delay_at = 0;

    /**
     * 是否删除
     *
     * @var int
     */
    public $deleted;

    /**
     * 是否已放到保留队列
     *
     * @var int
     */
    public $reserved;

    private $rawdata;

    /**
     * 获取job标识
     *
     * @return mixed 由vendor生成
     */
    abstract public function getJobId();

    /**
     * 获取job所需数据，由子类定义
     *
     * @return mixed
     */
    abstract public function getRawbody();

    public function fire()
    {
    }

    /**
     * @return mixed
     */
    public function payload()
    {
        if (is_null($this->rawdata)) {
            $this->rawdata = json_decode($this->getRawbody(), true);
        }

        return $this->rawdata;
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->deleted = true;
        return $this;
    }

    public function isDelete()
    {
        return $this->deleted;
    }

    public function expireAt()
    {
        return $this->payload()['expire_at'] ?? null;
    }

    public function queue()
    {
        return $this->queue;
    }

    public function connectionname()
    {
        return $this->connection_name;
    }
}