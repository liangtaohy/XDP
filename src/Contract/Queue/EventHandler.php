<?php
/**
 * 事件处理器接口定义
 *
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/1
 * Time: 10:31 AM
 */

namespace Xdp\Contract\Queue;


/**
 * Interface EventHandler
 *
 * handle是事件处理器的默认处理方法，若使用自定义方法，请遵循参数约定。
 *
 * @package Xdp\Contract\Queue
 */
interface EventHandler
{
    /**
     * default event handle method
     *
     * @param \Xdp\Queue\Event $event
     * @param string $payload
     * @return mixed
     */
    public function handle($event, $payload);
}