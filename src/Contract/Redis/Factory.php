<?php
/**
 * Redis工厂类
 *
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/22
 * Time: 上午9:57
 */
namespace Xdp\Contract\Redis;

interface Factory
{
    /**
     * 获取连接
     *
     * @param null $name
     * @return mixed
     */
    public function connection($name = null);
}