<?php
/**
 * 事件处理器接口定义
 *
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/1
 * Time: 10:31 AM
 */

namespace Xdp\Contract\Queue;


interface EventHandler
{
    /**
     * fire
     *
     * @param mixed $app
     * @param string $payload
     * @return mixed
     */
    public function fire($app, $payload);
}