<?php
/**
 * Queue
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/19
 * Time: 上午9:43
 */
namespace Xdp\Queue;

use Xdp\Utils\Str;

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

    /**
     * 重试规则
     *
     * @var
     */
    public $retryAfter;

    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getConnectionName()
    {
        return $this->connection_name;
    }

    public function setConnectionName($name)
    {
        $this->connection_name = $name;
        return $this;
    }

    public function createPayload($event, $data = null)
    {
        if (is_object($event)) {
            return $this->createObjectPayload($event, $data);
        } elseif (is_string($event)) {
            return $this->createStringPayload($event, $data);
        }

        throw new \InvalidArgumentException("invalid job");
    }

    protected function createObjectPayload(Event $event, $data = null)
    {
        $payload = $event->toArray();

        if (!is_null($data)) {
            $payload['data'] = $data;
        }

        return json_encode($payload);
    }

    protected function createStringPayload(string $event, $data = null)
    {
        $classname = explode("@", $event)[0];

        return json_encode([
            'id'    => Str::random(24),
            'name'   => $classname,
            'handler'   => $event,
            'data'      => $data,
            'create_at' => getMicroTime(),
            'attempts'  => null,
            'max_tries' => null
        ]);
    }
}