<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午6:51
 */

namespace Xdp\Container;

use Xdp\Container\Exception\InvalidKeyException;
use Xdp\Container\Exception\KeyExistsException;

/**
 * Class ObjectStorage
 * @package Xdp\Container
 */
class ObjectStorage
{

    /**
     * 保存单例类
     * @var array
     */
    private $store = [];

    /**
     * 保存绑定的类
     * @var array 
     */
    private $factory = [];


    /**
     * 保存实例化的类
     * @var array 
     */
    public $instance = [];

    /**
     * 保存key别名
     * @var array
     */
    private $alias = [];

    /**
     * 注册一个对象
     * @param string $key
     * @param  $closure
     * @throws KeyExistsException
     */
    public function instance(string $key,  $closure)
    {
        $this->checkKey($key);
        $this->instance[$key] = $closure;
    }

    /**
     * @param string $key
     * @param  $closure
     * @throws KeyExistsException
     */
    public function factory(string $key, $closure)
    {
        $this->checkKey($key);
        $this->factory[$key] = $closure;
    }

    /**
     * 注册一个单例对象
     * @param string $key
     * @param $object
     * @throws KeyExistsException
     */
    public function singleton(string $key, $object)
    {
        $this->checkKey($key);
        $this->instance[$key] = true;
        $this->store[$key] = $object;
    }

    /**
     * 注入一个已实例化的类
     * @param string $key
     * @param $object
     */
    public function store(string $key, $object)
    {
        $this->store[$key] = $object;
    }


    /**
     * 注入一个key别名
     * @param string $alias
     * @param string $key
     */
    public function alias(string $alias, string $key)
    {
        $this->alias[$alias] = $key;
    }


    /**
     * 别名是否存在
     * @param string $alias
     * @return bool
     */
    public function hasAlias(string $alias)
    {
        return isset($this->alias[$alias]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasInstance(string $key)
    {
        return isset($this->instance[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasFactory(string $key)
    {
        return isset($this->factory[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasStored(string $key)
    {
        return isset($this->store[$key]);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getInstance($key)
    {
        return $this->instance[$key];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getStored(string $key)
    {
        return $this->store[$key];
    }


    /**
     * @param string $key
     * @return mixed
     */
    public function getFactory(string $key)
    {
        return $this->factory[$key];
    }

    /**
     * 获取别名类
     * @param string $key
     * @return mixed
     */
    public function getAlias(string $key)
    {
        return $this->alias[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key)
    {
        if ($this->hasFactory($key)) {
            unset($this->factory[$key]);
            return ! $this->hasFactory($key);
        }

        if ($this->hasInstance($key)) {
            unset($this->instance[$key], $this->store[$key]);
            return ! $this->hasInstance($key);
        }
        
        if ($this->hasAlias($key)) {
            $alias_key = $this->getAlias($key);
            unset($this->alias[$key]);
            $this->remove($alias_key);
        }

        return false;
    }

    /**
     * 检测$key
     * @param string $key
     * @throws KeyExistsException
     */
    protected function checkKey(string $key)
    {
        /*if (! class_exists($key)) {
            throw new InvalidKeyException("Key [$key] was invalid. All keys must be valid class names");
        }*/

        if ($this->hasInstance($key) || $this->hasFactory($key)) {
            throw new KeyExistsException("Key [$key] already exists within the container");
        }
    }
}
