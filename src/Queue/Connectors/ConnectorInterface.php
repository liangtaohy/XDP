<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/19
 * Time: 下午5:48
 */

namespace Xdp\Queue\Connectors;


interface ConnectorInterface
{
    /**
     * 链路层的Connector
     *
     * @param array $config
     * @return mixed
     */
    public function connect(array $config);
}