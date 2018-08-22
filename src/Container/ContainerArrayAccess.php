<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午4:19
 */

namespace Xdp\Container;

use ArrayAccess;

/**
 * Class ContainerArrayAccess
 * @package Xdp\Container
 */
class ContainerArrayAccess implements ArrayAccess
{

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->add($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }
}