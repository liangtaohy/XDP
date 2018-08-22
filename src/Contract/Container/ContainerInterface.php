<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午6:48
 */

namespace Xdp\Contract\Container;


/**
 * Interface ContainerInterface
 * @package Xdp\Contract\Container
 */
interface ContainerInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function get(string $id);

    /**
     * @param $id
     * @return mixed
     */
    public function has(string $id) : bool;

}