<?php
/**
 * Queue
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/19
 * Time: 上午9:43
 */
namespace Xdp\Queue;

abstract class Queue
{
    /**
     * 连接名称
     *
     * @var string
     */
    protected $connection_name;

    /**
     * Ioc容器
     *
     * @var mixed
     */
    protected $container;

    public function getConnectionName()
    {
        return $this->connection_name;
    }

    public function setConnectionName($name)
    {
        return $this->connection_name = $name;
    }

    protected function createPayload($job, $data)
    {

    }

    public function setContainer($container)
    {
        $this->container = $container;
    }
}