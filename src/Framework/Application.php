<?php
/**
 * Created by PhpStorm.
 * User: xlegal
 * Date: 2018/8/23
 * Time: 下午3:57
 */

namespace Xdp\Framework;

use Xdp\Container\Container;

class Application extends Container
{
    /**
     * 应用名称
     * @note 原框架中的APP_NAME
     * @var string
     */
    protected $name;

    /**
     * 应用app所在目录
     * @var string
     */
    protected $path;

    /**
     * 应用app部署的根目录
     * @var string
     */
    protected $deployRoot;

    /**
     * app config目录
     * @var string
     */
    protected $appConfigDir;

    /**
     * app log目录
     * @var string
     */
    protected $appLogDir;

    /**
     * app log level
     * @var int
     */
    protected $logLevel;
}