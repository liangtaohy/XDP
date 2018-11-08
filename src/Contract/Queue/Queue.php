<?php
/**
 * 队列接口定义
 *
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/19
 * Time: 下午6:09
 */
namespace Xdp\Contract\Queue;

interface Queue
{
    public function push($event, $queue = null);

    public function later($event, $delay = 0, $queue = null);

    public function pop($queue = null);

    public function size($queue = null);

    public function getConnectionName();

    public function setConnectionName($name);
}