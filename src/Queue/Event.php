<?php
/**
 * Event基类
 *
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/6
 * Time: 11:44 AM
 */

namespace Xdp\Queue;


use Xdp\Utils\Str;

class Event
{
    protected $queue; // 队列名称
    protected $type; // 事件类型
    protected $data; // 数据区
    protected $attempts; // 尝试次数
    protected $delay; // 延后秒数
    protected $delay_at; // 延迟
    protected $created_at; // 创建时间
    protected $max_retries; // 最大重试次数
    protected $retry_after; // 在多久后重试

    protected $id; // event标识
    protected $name; // event名称

    protected $handler; // 处理器

    protected $instance;

    /**
     * IoC容器
     *
     * @var \Xdp\Contract\Container\ContainerInterface
     */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * 触发事件，调用hander处理该事件
     *
     * @return mixed
     */
    public function fire()
    {
        list($class, $method) = explode("@", $this->handler);
        $this->instance = $this->container->make($class);
        return $this->instance->{$method}($this->data);
    }

    public function getId()
    {
        if (is_null($this->id)) {
            $this->id = Str::random(24);
        }

        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * 设置队列名称
     *
     * @param $queue
     * @return $this
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * 获取队列名称
     * @return mixed
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * 设置事件类型
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 返回事件类型
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 设置payload区（数据区）
     *
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 返回event所需要的数据
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * attempts自增
     */
    public function incrAttempts()
    {
        return $this->attempts++;
    }

    /**
     * 返回attempts
     * @return mixed
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * 设置延迟的秒数（ttl）
     * @param int $delay
     * @return $this
     */
    public function setDelay(int $delay)
    {
        $this->delay = $delay;
        $this->delay_at = time() + $delay;
        return $this;
    }

    /**
     * 返回延迟的时间
     * @return mixed
     */
    public function getDelayAt()
    {
        return $this->delay_at;
    }

    /**
     * 获取剩余delay数 (秒)
     *
     * @return int
     */
    public function getDelayTtl()
    {
        return $this->delay_at - time();
    }

    /**
     * 设置创建时间,秒为单位
     * 默认为当前时间now
     *
     * @param $create_at
     * @return $this
     */
    public function setCreateAt(int $create_at = 0)
    {
        $this->created_at = $create_at ?? time();
        return $this;
    }

    /**
     * 返回事件创建时间, 秒
     *
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->created_at;
    }

    /**
     * 重试配置
     *
     * @param mixed $retry_after
     * @return $this
     */
    public function setRetryAfter($retry_after)
    {
        $this->retry_after = $retry_after;
        return $this;
    }

    /**
     * 获取重试配置
     *
     * @return mixed
     */
    public function getRetryAfter()
    {
        return $this->retry_after;
    }

    /**
     * 设置最大重试次数
     *
     * @param $max_retries
     * @return $this
     */
    public function setMaxRetries($max_retries)
    {
        $this->max_retries = $max_retries;
        return $this;
    }

    /**
     * 获取最大重试次数
     *
     * @return mixed
     */
    public function getMaxRetries()
    {
        return $this->max_retries;
    }

    /**
     * 设置处理器
     *
     * @param $handler
     * @return $this
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * 返回处理器
     *
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * to Array
     * @return array
     */
    public function toArray()
    {
        return [
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'queue'         => $this->queue,
            'type'          => $this->type,
            'data'          => $this->data ?? null,
            'attempts'      => $this->attempts ?? 0,
            'delay'         => $this->delay ?? 0, // 延后秒数
            'delay_at'      => $this->delay_at ?? 0,
            'created_at'    => $this->created_at ?? time(),
            'max_retries'   => $this->max_retries ?? 0,
            'retry_after'   => $this->retry_after ?? null,
            'handler'       => $this->handler
        ];
    }

    /**
     * 从payload中恢复Event
     *
     * @param $payload
     * @return Event
     */
    public static function load($container, $payload)
    {
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }

        $e          = new Event($container);
        $e->id      = $payload['id'];
        $e->name    = $payload['name'];
        $e->queue   = $payload['queue'] ?? '';
        $e->type    = $payload['type'] ?? 0;
        $e->created_at  = $payload['created_at'] ?? 0;
        $e->delay_at    = $payload['delay_at'] ?? 0;
        $e->delay   = $payload['delay'] ?? 0;
        $e->attempts    = $payload['attempts'] ?? 0;
        $e->max_retries = $payload['max_retries'] ?? 0;
        $e->retry_after = $payload['retry_after'] ?? null;
        $e->handler = $payload['handler'];
        $e->data = $payload['data'];

        return $e;
    }
}