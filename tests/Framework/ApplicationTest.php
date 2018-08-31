<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/30
 * Time: 下午3:05
 */

require __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use \Xdp\Framework\Application;
use \Xdp\Contract\Debug\ExceptionHandler;

class ApplicationTest extends TestCase
{
    static $bootstrappers = [
        \Xdp\Framework\Foundation\Bootstrap\LoadEnvironmentVars::class,
        \Xdp\Framework\Foundation\Bootstrap\LoadLocals::class,
        \Xdp\Framework\Foundation\Bootstrap\HandleException::class,
    ];

    public function testGlobalExceptionHandler()
    {
        $app = new Application("test", __DIR__);
        //$app->make(ExceptionHandler::class, Xdp\Framework\Foundation\Exception\ConcreteExceptionHandler::class);
        $app->instance(ExceptionHandler::class, new Xdp\Framework\Foundation\Exception\ConcreteExceptionHandler);
        $app->bootstrapWith(self::$bootstrappers);

        $this->assertEquals("test", $app->appName());
        $this->assertEquals('development', env('APP_ENV'));
        $this->assertEquals("http://localhost", env('APP_URL'));

        echo $app['path'] . PHP_EOL;
        echo $app['path.base'] . PHP_EOL;

        new cc();
    }
}

(new ApplicationTest())->testGlobalExceptionHandler();