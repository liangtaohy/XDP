<?php

namespace Xdp\Sms\Middleware;

/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/26 13341007105@163.com
 * Time: 6:36 PM
 */

use Closure;
use Xdp\Contract\Pipeline\MiddlewareInterface;
use Xdp\Sms\Exception\SmsException;

class SmsBlackMiddleware implements MiddlewareInterface
{
    public function handle($mobile, Closure $next)
    {
        // TODO: 黑名单规则
//        throw new SmsException($mobile.' in black list');
    }
}
