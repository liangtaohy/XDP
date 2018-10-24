<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/19
 * Time: 下午6:09
 */
namespace Xdp\Contract\Queue\Queue;

interface Queue
{
    public function push($queue, $job, $data = null);

    public function later($queue, $job, $data = null, $delay = 0);

    public function size($queue);

    public function getConnectionName();

    public function setConnectionName($name);
}