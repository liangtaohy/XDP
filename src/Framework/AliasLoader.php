<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/28
 * Time: 下午4:14
 */

namespace Xdp\Framework;

class AliasLoader
{
    /**
     * 别名数组
     *
     * @var array
     */
    protected $aliases;

    /**
     * 是否已注册到autoloader stack中
     *
     * @var bool
     */
    private $registered = false;

    /**
     * 单例
     *
     * @var \Xdp\Framework\AliasLoader
     */
    private static $instance;

    public function __construct(array $aliases)
    {
        $this->aliases = $aliases;
    }

    public static function getInstance(array $aliases)
    {
        if (is_null(static::$instance)) {
            return static::$instance = new static($aliases);
        }

        $aliases = array_merge(static::$instance->aliases(), $aliases);
        static::$instance->setAliases($aliases);

        return static::$instance;
    }

    public function load($alias)
    {
        if (isset($this->aliases[$alias])) {
            return class_alias($this->aliases[$alias], $alias);
        }
    }

    public function register()
    {
        if (!$this->registered) {
            spl_autoload_register([$this, 'load'], true, true);
            $this->registered = true;
        }
    }

    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
    }

    public function aliases()
    {
        return $this->aliases;
    }
}