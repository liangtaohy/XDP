<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午4:18
 */
declare(strict_types=1);

namespace Xdp\Container;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Xdp\Container\Exception\ContainerException;
use Xdp\Container\Exception\NotFoundException;
use Xdp\Contract\Container\ContainerInterface;

/**
 * Class Container
 * @package Xdp\Container
 */
class Container extends ContainerArrayAccess implements ContainerInterface
{

    /**
     * 对象容器类
     * @var ObjectStorage
     */
    private $storage;

    /**
     * 对象参数容器
     * @var array
     */
    private $stack = [];

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->storage = new ObjectStorage();
    }

    /**
     * 注入一个对象
     * 通过该方法注入类时 $closure 必须为一个闭包函数
     * @param string $key  检索时的 $key
     * @param callable $closure
     * @throws Exception\InvalidKeyException
     * @throws Exception\KeyExistsException
     */
    public function add(string $key, callable $closure)
    {
        $this->storage->factory($key, $closure);
    }

    /**
     * 获取一个实例化好的对象
     * @param $key
     * @return mixed
     * @throws ContainerException
     * @throws NotFoundException
     */
    public function get(string $key)
    {
        if (! \is_string($key) || empty($key)) {
            throw new ContainerException('$key must be a string');
        }

        if ($this->storage->hasStored($key)) {
            return $this->storage->getStored($key);
        }

        // key exists but hasn't been initialized yet
        if ($this->storage->hasObject($key)) {
            $this->storage->store($key, $this->storage->getDefinition($key)($this));
            return $this->storage->getStored($key);
        }

        if ($this->storage->hasFactory($key)) {
            $definition = $this->storage->getFactory($key);
            return $definition($this);
        }

        // the key was not found
        throw new NotFoundException("The key [$key] could not be found");
    }


    /**
     * 检测给定值是否存在
     * @param $key
     * @return bool|mixed
     */
    public function has(string $key):bool
    {
        return $this->storage->hasObject($key) || $this->storage->hasFactory($key);
    }

    /**
     * 注入一个对象
     * @param string $key
     * @param callable $closure
     * @throws Exception\InvalidKeyException
     * @throws Exception\KeyExistsException
     */
    public function addSingleton(string $key, callable $closure)
    {
        $this->storage->object($key, $closure);
    }

    /**
     * 注入一个实例化好的对象
     * @param $key
     * @param null $object
     * @throws ContainerException
     * @throws Exception\InvalidKeyException
     * @throws Exception\KeyExistsException
     */
    public function addInstance($key, $object = null)
    {
        if (\is_object($key)) {
            $this->storage->instance(\get_class($key), $key);
        } elseif (\is_object($object)) {
            $this->storage->instance($key, $object);
        } else {
            throw new ContainerException('An object instance must be passed');
        }
    }

    /**
     * 删除$key
     * @param string $key
     * @return bool
     */
    public function remove(string $key)
    {
        return $this->storage->remove($key);
    }

    /**
     * 返回一个新实例
     * @param string $class
     * @param array $args
     * @return object
     * @throws ContainerException
     */
    public function resolve(string $class, array $args = [])
    {
        $this->stack[] = [];

        try {
            $reflectionClass = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new ContainerException($e->getMessage());
        }

        // get the constructor method of the current class
        $constructor = $reflectionClass->getConstructor();

        // if there is no constructor, just return new instance
        if ($constructor === null) {
            $this->resetStack();
            return new $class;
        }

        // get constructor params
        $params = $constructor->getParameters();

        // If there is a constructor, but no params
        if (\count($params) === 0) {
            $this->resetStack();
            return new $class;
        }
        $this->resolveParams($params, $args);

        $resolutions = end($this->stack);
        $this->resetStack();

        // return the resolved class
        return $reflectionClass->newInstanceArgs($resolutions);
    }

    /**
     * 返回$class的$method方法
     * @param $class
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws ContainerException
     */
    public function resolveMethod($class, string $method, array $args = [])
    {
        if (! \is_callable([$class, $method])) {
            throw new ContainerException("$class::$method does not exist or is not callable so could not be resolved");
        }

        $reflectionMethod = new ReflectionMethod($class, $method);

        if ($reflectionMethod->isStatic()) {
            $classInstance = null;
        } elseif (\is_string($class)) {
            $classInstance = new $class();
        } else {
            $classInstance = $class;
        }

        //获取Method需要的参数
        $params = $reflectionMethod->getParameters();
        if (\count($params) === 0) {
            if ($reflectionMethod->isStatic() === true) {
                $this->resetStack();
                return $reflectionMethod->invoke(null);
            } else {
                $this->resetStack();
                return $reflectionMethod->invoke($classInstance);
            }
        }

        $this->resolveParams($params, $args);

        $resolutions = end($this->stack);
        $this->resetStack();

        return $reflectionMethod->invokeArgs($classInstance, $resolutions);
    }

    /**
     * 删除stack数组中第一个值
     */
    private function resetStack()
    {
        array_pop($this->stack);
    }

    /**
     * 添加类或方法需要的参数
     * @param $value
     */
    private function addToStack($value)
    {
        $keys = array_keys($this->stack);
        $this->stack[end($keys)][] = $value;
    }

    /**
     * 在容器中搜索类名 如果存在 则添加到相对应的请求方法参数中
     * @param string $className
     * @return bool
     * @throws ContainerException
     * @throws NotFoundException
     */
    private function resolveFromContainer(string $className):bool
    {
        if ($this->has($className)) {
            $this->addToStack($this->get($className));
            return true;
        }
        return false;
    }

    /**
     * 获取需要的多个参数
     * @param $params
     * @param $args
     * @throws ContainerException
     * @throws NotFoundException
     */
    private function resolveParams($params, $args)
    {
        foreach ($params as $param) {
            $class = $param->getClass();

            if (is_null($class)) {
                $this->resolveParam($args, $param);
                continue;
            }

            $className = $class->getName();

            if ($this->resolveFromContainer($className)) {//如果class存在容器里
                continue;
            }

            $this->addToStack($this->resolve($className, $args));//获取class实例 添加到容器
        }
    }

    /**
     * 获取需要的参数
     * @param array $args
     * @param ReflectionParameter $param
     */
    private function resolveParam(array $args, ReflectionParameter $param)
    {
        $name = $param->name;

        if (isset($args[$name])) {
            $this->addToStack($args[$name]);
            return;
        }

        if ($param->isDefaultValueAvailable()) {
            $this->addToStack($param->getDefaultValue());
        }
    }
}
