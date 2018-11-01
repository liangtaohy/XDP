<?php

namespace Xdp\Sms\Middleware;

/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/26 13341007105@163.com
 * Time: 5:22 PM
 */


use Closure;
use Xdp\Contract\Pipeline\MiddlewareInterface;

class SmsLimitMiddleware implements MiddlewareInterface
{

    public function handle($mobile, Closure $next)
    {
        //TODO 手机号限制逻辑
        return $next($mobile);
    }
}
