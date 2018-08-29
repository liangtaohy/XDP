<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/28
 * Time: 下午5:14
 */

namespace Xdp\Framework;


abstract class Facades
{
    /**
     * Facade instances array
     *
     * @var array
     */
    protected static $resolvedInstance = [];

    /**
     * Application
     *
     * @var \Xdp\Container\Container
     */
    protected static $app;

    /**
     * Facade Accessor
     *
     * @note This method should be overridden by sub-class.
     *
     * @throws \RuntimeException
     * @return string|\Object
     */
    public static function getFacadeAccessor()
    {
        throw new \RuntimeException("should be implemented");
    }

    /**
     * 获取Facade实例
     *
     * @return mixed
     */
    public static function getFacadesRoot()
    {
        return static::resolveFacadesInstance(static::getFacadeAccessor());
    }

    /**
     * 根据name，解析facades实例
     *
     * @param $name
     * @return mixed
     */
    public static function resolveFacadesInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        return static::$resolvedInstance[$name] = static::$app[$name];
    }

    /**
     * 获取application
     *
     * @return \Xdp\Container\Container
     */
    public static function getApplication()
    {
        return static::$app;
    }

    /**
     * 设置application
     *
     * @param $app
     */
    public static function setApplication($app)
    {
        static::$app = $app;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadesRoot();
        if (!isset($instance)) {
            throw new \RuntimeException("A Facade root should be set!");
        }
        return $instance->{$method}(...$arguments);
    }
}