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
     * @var array
     */
    private $definitions = [
        'instance' => [],
        'factory'  => [],
    ];

    /**
     * @var array
     */
    private $store = [];

    /**
     * @param string $key
     * @param callable $closure
     * @throws InvalidKeyException
     * @throws KeyExistsException
     */
    public function object(string $key, callable $closure)
    {
        $this->checkKey($key);
        $this->definitions['instance'][$key] = $closure;
    }

    /**
     * @param string $key
     * @param callable $closure
     * @throws InvalidKeyException
     * @throws KeyExistsException
     */
    public function factory(string $key, callable $closure)
    {
        $this->checkKey($key);
        $this->definitions['factory'][$key] = $closure;
    }

    /**
     * @param string $key
     * @param $object
     * @throws InvalidKeyException
     * @throws KeyExistsException
     */
    public function instance(string $key, $object)
    {
        $this->checkKey($key);
        $this->definitions['instance'][$key] = true;
        $this->store[$key] = $object;
    }

    /**
     * @param string $key
     * @param $object
     */
    public function store(string $key, $object)
    {
        $this->store[$key] = $object;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasObject(string $key)
    {
        return isset($this->definitions['instance'][$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasFactory(string $key)
    {
        return isset($this->definitions['factory'][$key]);
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
    public function getDefinition($key)
    {
        return $this->definitions['instance'][$key];
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
        return $this->definitions['factory'][$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key)
    {
        if ($this->hasFactory($key)) {
            unset($this->definitions['factory'][$key]);
            return ! $this->hasFactory($key);
        }

        if ($this->hasObject($key)) {
            unset($this->definitions['instance'][$key], $this->store[$key]);
            return ! $this->hasObject($key);
        }

        return false;
    }

    /**
     * 检测$key
     * @param string $key
     * @throws InvalidKeyException
     * @throws KeyExistsException
     */
    protected function checkKey(string $key)
    {
        if (! class_exists($key)) {
            throw new InvalidKeyException("Key [$key] was invalid. All keys must be valid class names");
        }

        if ($this->hasObject($key) || $this->hasFactory($key)) {
            throw new KeyExistsException("Key [$key] already exists within the container");
        }
    }
}
