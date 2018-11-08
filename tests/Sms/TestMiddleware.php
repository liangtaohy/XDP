<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/11/1 13341007105@163.com
 * Time: 7:49 PM
 */

namespace Xdp\Test\Sms;

require_once __DIR__.'/../../vendor/autoload.php';


use Closure;
use Xdp\Contract\Pipeline\MiddlewareInterface;
use Xdp\Sms\SmsManager;
use Xdp\Test\XdpTestCase;

class TestMiddleware extends XdpTestCase
{
    public function testCaseOne()
    {
        SmsManager::sendVoice(13341007105,1234);
    }
}

class MiddlewareOne implements MiddlewareInterface{

    public function handle($request, Closure $next)
    {
        echo 'MiddlewareTwo enter'.PHP_EOL;
        return $next($request);
    }
}

class MiddlewareTwo implements MiddlewareInterface{

    public function handle($request, Closure $next)
    {
        echo 'MiddlewareTwo enter'.PHP_EOL;
        return $next($request);
    }
}

class MiddlewareThree implements MiddlewareInterface{

    public function handle($request, Closure $next)
    {
        echo 'MiddlewareTwo enter'.PHP_EOL;
        return $next($request);
    }
}