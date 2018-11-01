<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/5 13341007105@163.com
 * Time: 下午12:54
 */

namespace Xdp\Test;


use PHPUnit\Framework\TestCase;
use Xdp\Framework\Application;
use Xdp\Contract\Debug\ExceptionHandler;
use Xdp\Framework\Foundation\Exception\ConcreteExceptionHandler;
use Xdp\Sms\Adapter\AliDaYuAdapter;
use Xdp\Sms\Adapter\QCloudAdapter;


class XdpTestCase extends TestCase
{
    public static $bootstrappers = [
        \Xdp\Framework\Foundation\Bootstrap\LoadEnvironmentVars::class,
        \Xdp\Framework\Foundation\Bootstrap\LoadConfiguration::class,
        \Xdp\Framework\Foundation\Bootstrap\HandleException::class,
    ];



    public static function runApp()
    {

        $app = new Application("test", __DIR__."/Framework");
        $app->instance(ExceptionHandler::class, new ConcreteExceptionHandler);
        $app->bootstrapWith(self::$bootstrappers);


        $GLOBALS['LOG'] = [
            'log_file' => env("APP_LOG_FILE"),
            'log_level' => env("APP_LOG_LEVEL")
        ];
    }
}

