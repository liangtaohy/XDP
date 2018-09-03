<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/9/3
 * Time: 上午9:01
 */
namespace Xdp\Config;

use ArrayAccess;
use Xdp\Utils\Arr;

class Config implements ArrayAccess
{
    protected $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * 判断key是否存在
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return Arr::has($this->items, $key);
    }

    /**
     * 获取key的值，如果不存在，则返回default指定的值
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * 设置key的值为value
     *
     * @param mixed $key
     * @param null $value
     * @return $this
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];
        foreach ($keys as $key => $value) {
            Arr::set($this->items, $key, $value);
        }
        return $this;
    }

    /**
     * 返回所有的config元素
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * 检查数组的key是否存在
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * 返回数组key对应的值
     *
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set a configuration option.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Unset a configuration option.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->set($key, null);
    }
}